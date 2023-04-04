export default class {
    constructor(programForm, selector) {
        this.selector = selector;

        $.jstree.defaults.dnd.touch = true;
        $.jstree.defaults.dnd.copy = false;

        this.fetchProgramTree();

        // On selecting a program.
        selector.on('select_node.jstree', (e, node) => programForm.fetchProgram(node.selected[0]));

        // Expand programs when jstree is loaded.
        selector.on('loaded.jstree', () => selector.jstree('open_all'));

        // On updating program tree.
        this.selector.on('move_node.jstree', (e, data) => {
            this.updateProgramTree(data);
        });
    }

    fetchProgramTree() {
        this.selector.jstree({
            core: {
                data: { url: route('admin.programs.tree') },
                check_callback: true,
            },
            plugins: ['dnd'],
        });
    }

    updateProgramTree(data) {
        this.loading(data.node, true);

        $.ajax({
            type: 'PUT',
            url: route('admin.programs.tree.update'),
            data: { program_tree: this.getProgramTree() },
            success: (message) => {
                success(message);

                this.loading(data.node, false);
            },
            error: (xhr) => {
                error(xhr.responseJSON.message);

                this.loading(data.node, false);
            },
        });
    }

    getProgramTree() {
        let programs = this.selector.jstree(true).get_json('#', { flat: true });

        return programs.reduce((formatted, program) => {
            return formatted.concat({
                id: program.id,
                parent_id: (program.parent === '#') ? null : program.parent,
                position: program.data.position,
            });
        }, []);
    }

    loading(node, state) {
        let nodeElement = this.selector.jstree().get_node(node, true);

        if (state) {
            $(nodeElement).addClass('jstree-loading');
        } else {
            $(nodeElement).removeClass('jstree-loading');
        }
    }
}
