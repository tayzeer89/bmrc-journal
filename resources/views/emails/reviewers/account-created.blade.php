<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>
        BMRC Reviewer Account
    </title>

</head>

<body style="
    font-family:Arial, Helvetica, sans-serif;
    background:#f5f5f5;
    padding:30px;
">

<div style="
    max-width:650px;
    margin:auto;
    background:#ffffff;
    padding:30px;
    border-radius:8px;
">

    <h2>
        BMRC Reviewer Account
    </h2>


    <p>
        Dear {{ $reviewer->name }},
    </p>


    <p>
        A reviewer account has been created
        for you in the BMRC Online Article
        Submission and Editorial Management
        System.
    </p>


    <p>
        Please use the following credentials
        to log in.
    </p>


    <table
        width="100%"
        cellpadding="10"
        style="
            border-collapse:collapse;
            background:#f8f9fa;
        "
    >

        <tr>

            <td>
                <strong>Email</strong>
            </td>

            <td>
                {{ $reviewer->email }}
            </td>

        </tr>


        <tr>

            <td>
                <strong>
                    Temporary Password
                </strong>
            </td>

            <td>
                {{ $temporaryPassword }}
            </td>

        </tr>


        <tr>

            <td>
                <strong>
                    Reviewer Login
                </strong>
            </td>

            <td>

                <a
                    href="{{ route('reviewer.login') }}"
                >
                    Login to Reviewer Portal
                </a>

            </td>

        </tr>

    </table>


    <p style="margin-top:25px;">

        You will be required to change
        your temporary password after
        your first login.

    </p>


    <p>

        After changing your password,
        please complete your reviewer
        profile and submit it for approval.

    </p>


    <p style="margin-top:30px;">

        Regards,<br>

        <strong>
            Editorial Office
        </strong><br>

        Bangladesh Medical Research Council

    </p>

</div>

</body>

</html>