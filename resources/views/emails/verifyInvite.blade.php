<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style type="text/css">{{ file_get_contents(app_path() . '/../vendor/snowfire/beautymail/src/styles/css/minty.css') }}</style>
    @if(isset($css))
        <style type="text/css">
            {{ $css }}
        </style>
    @endif
</head>
<body>
<table width="100%" bgcolor="#f6f4f5" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
    <tbody>
    <tr>
        <td>
            <table width="100%" style="max-width: 920px" bgcolor="{{ Config::get('beautymail.colors.highlight', '#0b7685') }}" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" hlitebg="edit" shadow="edit">
                <tbody>
                <tr>
                    <td>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table bgcolor="#ffffff"  width="100%" style="max-width: 920px" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" modulebg="edit">
                <tbody>
                <tr>
                    <td width="100%" height="20"></td>
                </tr>
                <tr>
                    <td>
                        <table width="540" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
                            <tbody>
                            <tr>
                                <td class="title">
                                    <h1>Hello {{$user->name}}</h1>
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" height="10"></td>
                            </tr>
                            <tr>
                                <td class="paragraph">
                                    {{$body}}
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" height="25"></td>
                            </tr>
                            <tr>
                                <td>
                                    @include('beautymail::templates.minty.button', ['text' => 'Accept invite', 'link' => $url])
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" height="25"></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
