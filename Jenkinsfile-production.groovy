#!/usr/bin/env groovy
pipeline {
    agent any
    environment {
        SRC_PATH = "${JENKINS_HOME}/jobs/${JOB_NAME}/builds/${BUILD_NUMBER}/archive"
        ZIP_FILE = "release.zip"
        DEST_HOST = "euprojects.ro"
        DEST_IP = "128.199.45.244"
        DEST_HOST_USER = "jenkins"
        DEST_PATH = "/home/jenkins/api.euprojects.ro/releases"
        RELEASE_FOLDER = "${BUILD_NUMBER}"
        SHARED_FOLDER = '/home/jenkins/api.euprojects.ro/shared'
        ENV_SYM_SRC = '.env'
        STORAGE_SYM_SRC = 'storage'
        PUBLIC_STORAGE_SYM_SRC = 'storage/app/storage'
        ENV_SYM = '.env'
        STORAGE_SYM = 'storage'
        PUBLIC_STORAGE_SYM = 'public/storage'
        RELEASE_SYMLINK = '/var/www/api.euprojects.ro/html'
    }
    stages {
        stage('Build') {
            steps {
                sh("composer update")
                sh("npm install lerna yarn")
                sh("npm install --save-dev")
                sh("npm run prod")
            }
        }
        stage('Deploy') {
            steps {
                // archive and copy build archive
                sh("rm -rf storage/")
                sh("rm -f ${ZIP_FILE}")
                script {
                    zip zipFile: "${ZIP_FILE}", archive: true, dir: '.'
                }
                sh("scp ${SRC_PATH}/${ZIP_FILE} ${DEST_HOST_USER}@${DEST_IP}:${DEST_PATH}")
                // unzip build
                sh("""ssh ${DEST_HOST_USER}@${DEST_IP} '''
                    cd ${DEST_PATH};
                    mkdir ${RELEASE_FOLDER};
                    unzip ${ZIP_FILE} -d ${RELEASE_FOLDER};
                    rm ${ZIP_FILE}
                '''""")
                // symlinks
                sh("""ssh ${DEST_HOST_USER}@${DEST_IP} '''
                    ln -s ${SHARED_FOLDER}/${ENV_SYM_SRC} ${DEST_PATH}/${RELEASE_FOLDER}/${ENV_SYM};
                    ln -sn ${SHARED_FOLDER}/${STORAGE_SYM_SRC} ${DEST_PATH}/${RELEASE_FOLDER}/${STORAGE_SYM};
                    ln -sn ${SHARED_FOLDER}/${PUBLIC_STORAGE_SYM_SRC} ${DEST_PATH}/${RELEASE_FOLDER}/${PUBLIC_STORAGE_SYM};
                    ln -sfn ${DEST_PATH}/${RELEASE_FOLDER} ${RELEASE_SYMLINK};
                '''""")
                // db migrate
                sh("""ssh ${DEST_HOST_USER}@${DEST_IP} '''
                    cd ${DEST_PATH}/${RELEASE_FOLDER};
                    chmod -R 775 bootstrap;
                    yes | php artisan migrate;
                    php artisan config:cache;
                    php artisan route:cache;
//                     php artisan cache:clear;
                    php artisan view:clear;
//                     chown www-data:www-data storage/framework/cache/data/cache;
//                     chown www-data:www-data storage/framework/cache/data/cache/*;
//                     chown jenkins:www-data bootstrap/cache/routes-v7.php;
                    supervisorctl reload;
                '''""")
                // rotate releases to 5 folders max
                sh("ssh ${DEST_HOST_USER}@${DEST_IP} 'cd ${DEST_PATH}; ls -lt | tail -n +7 | awk '\\''{print \$9}'\\'' | xargs -r rm -r'")
            }
        }
    }
    post {
        success {
            slackSend (color: '#00FF00', channel: "#deploy-prod", message: "SUCCESSFUL: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
        }
        failure {
            slackSend (color: '#FF0000', channel: "#deploy-prod", message: "FAILED: Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
        }
    }
}
