@extends('layouts.email')
@section('content')
<tr>
    <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td class="content-cell">
                    <div class="f-fallback">
                        <h1>Hi {{$user->first_name}},</h1>
                        <p>You recently requested to reset your password for your {{$app_name}} account. Use the button below to reset it. <strong>This password reset is only valid for the next 24 hours.</strong></p>
                        <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td align="center">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
                                        <tr>
                                            <td align="center">
                                                <a style="color: #ffffff" href="{{$action_url}}" class="f-fallback button button--green" target="_blank">Click to reset your password</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <p>Thanks,<br>Stefan {{$app_user}} from {{$app_name}}</p>
                        <table class="body-sub" role="presentation">
                            <tr>
                                <td>
                                    <p class="f-fallback sub">If you’re having trouble with the button above, copy and paste the URL below into your web browser.</p>
                                    <p class="f-fallback sub">{{$action_url}}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>
@stop
