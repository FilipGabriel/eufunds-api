<!DOCTYPE html>
<html lang="en" style="-ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%;
                    -webkit-print-color-adjust: exact;"
>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400" rel="stylesheet">
    </head>

    <body style="font-family: 'Open Sans', sans-serif;
                font-size: 15px;
                min-width: 320px;
                margin: 0;"
    >
        <table style="border-collapse: collapse; width: 100%;">
            <tbody>
                <tr>
                    <td style="padding: 0;">
                        <table style="border-collapse: collapse; width: 100%;">
                            <tbody>
                                <tr>
                                    <td style="background: {{ mail_theme_color() }}; text-align: center;">
                                        @if (is_null($logo))
                                            <h5 style="font-size: 30px;
                                                    line-height: 36px;
                                                    margin: 0;
                                                    padding: 30px 15px;
                                                    text-align: center;"
                                            >
                                                <a href="{{ config('app.frontend_domain') }}" style="font-family: 'Open Sans', sans-serif;
                                                                                    font-weight: 400;
                                                                                    color: #ffffff;
                                                                                    text-decoration: none;"
                                                >
                                                    {{ setting('app_name') }}
                                                </a>
                                            </h5>
                                        @else
                                            <div style="display: flex;
                                                        height: 64px;
                                                        width: 200px;
                                                        align-items: center;
                                                        justify-content: center;
                                                        margin: auto;
                                                        padding: 16px 15px;"
                                            >
                                                <img src="{{ $logo }}" style="max-height: 100%; max-width: 100%;" alt="logo">
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 40px 15px;">
                        <table style="border-collapse: collapse;
                                    min-width: 320px;
                                    max-width: 600px;
                                    width: 100%;
                                    margin: auto;"
                        >
                            <tr>
                                <td style="padding: 0;">
                                    <h4 style="font-family: 'Open Sans', sans-serif;
                                            font-weight: bold;
                                            font-size: 18px;
                                            line-height: 26px;
                                            margin: 0 0 15px;
                                            color: #3d4852;"
                                    >
                                        {{ trans('appfront::mail.hello', ['name' => $user->name]) }}
                                    </h4>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 0;">
                                    <span style="font-family: 'Open Sans', sans-serif;
                                                font-weight: 400;
                                                font-size: 16px;
                                                line-height: 26px;
                                                color: #666666;
                                                display: block;"
                                    >
                                        {{ trans('user::mail.received_a_password_reset_request') }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 30px 0; text-align: center;">
                                    <a href="{{ $url }}" style="box-sizing: border-box; 
                                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; 
                                        position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; 
                                        display: inline-block; overflow: hidden; text-decoration: none; 
                                        background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; 
                                        border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;"
                                    >
                                        {{ trans('user::mail.reset_password') }}
                                    </a>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 0;">
                                    <span style="font-family: 'Open Sans', sans-serif;
                                                font-weight: 400;
                                                font-size: 16px;
                                                line-height: 26px;
                                                color: #666666;
                                                display: block;"
                                    >
                                        {{ trans('user::mail.no_further_action_is_required') }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding-top: 20px;">
                                    <span style="font-family: 'Open Sans', sans-serif;
                                                font-weight: 400;
                                                font-size: 16px;
                                                line-height: 26px;
                                                color: #666666;
                                                display: block;"
                                    >
                                        {!! trans('user::mail.thanks') !!}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 23px 0 0;">
                                    <span style="font-family: 'Open Sans', sans-serif;
                                                font-weight: 400;
                                                font-size: 15px;
                                                line-height: 24px;
                                                display: block;
                                                padding: 5px 0 10px;
                                                color: #666666;
                                                border-top: 1px solid #e9e9e9;"
                                    >
                                        {{ trans('user::mail.if_you\’re_having_trouble', ['button_name' => trans('user::mail.reset_password')]) }}
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding: 0;">
                                    <a href="{{ $url }}" style="font-family: 'Open Sans', sans-serif;
                                                                font-weight: 400;
                                                                font-size: 16px;
                                                                line-height: 26px;
                                                                text-decoration: underline;
                                                                color: #31629f;
                                                                word-break: break-all;"
                                    >
                                        {{ $url }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 15px 0; background: #f1f3f7; text-align: center;">
                        <span style="font-family: 'Open Sans', sans-serif;
                                    font-weight: 400;
                                    font-size: 16px;
                                    line-height: 26px;
                                    display: inline-block;
                                    color: #555555;
                                    padding: 0 15px;"
                        >
                            &copy; {{ date('Y') }}
                            <a target="_blank" href="{{ config('app.frontend_domain') }}" style="text-decoration: none; color: #31629f;">
                                {{ setting('app_name') }}.
                            </a>
                            {{ trans('appfront::mail.all_rights_reserved') }}
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
