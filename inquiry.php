<?php

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);

require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');
require_once(JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'platform.php');

$mainframe =& JFactory::getApplication('site');

$mailer = JFactory::getMailer();
$config = JFactory::getConfig();

$mailer->setSender(array(
    $config->get('mailfrom'),
    $config->get('fromname')
));

$to = 'dancedenee@villagedancetheatre.com';
$bcc = 'webmaster@villagedancetheatre.com';
$subject = 'Registration Inquiry from villagedancetheatre.com';

$mailer->addRecipient($to);
$mailer->addBcc($bcc);

// convert array to object
$request = json_decode(json_encode($_REQUEST));

if (!isValidRequest($request)) {
    $out = json_encode(array(
        'status' => 'error',
        'heading' => 'Sorry, something is not right with your submission.',
        'body' => 'Please double check you have completed the fields and checked the verification box.<br>'
            . '<a class="close back-link" aria-label="Close"">Return to the form</a>'
    ));
    outputJSON($out);
    die;
}

$children = array();

foreach ($_REQUEST as $key => $val) {
    if (preg_match('/^inputChild(\d+)(.*?)$/i', $key, $matches)) {
        if (!is_object($children[$matches[1]])) {
            $children[$matches[1]] = new stdClass();
        }
        $children[$matches[1]]->{$matches[2]} = $val;
    }
}

$body = '<p><strong>' . $request->inputName . '</strong> submitted an online inquiry about registration.</p>';

$body .= strlen($request->inputNotes) ?
                    '<blockquote>' . $request->inputNotes . '</blockquote>'
                    : '';

$body .= isset($request->inputStudioClass) ?
                    '<blockquote>I\'m looking for In-Studio classes.</blockquote>'
                    : '';

$body .= isset($request->inputZoomClass) ?
                    '<blockquote>I\'m looking for Video classes via Zoom.</blockquote>'
                    : '';

$body .= isset($request->inputHybridClass) ?
                    '<blockquote>I\'m open to a combination of In-Studio and Video/Zoom classes.</blockquote>'
                    : '';

$body .= isset($request->inputClassVisit) ?
                    '<blockquote>I\'d like to arrange a class visit.</blockquote>'
                    : '';

$body .= isset($request->inputPerfVisit) ?
                    '<blockquote>I\'d like to arrange seeing an upcoming performance.</blockquote>'
                    : '';

$body .= '<ul><strong>Contact Info</strong><br>';
$body .= '	<li>' . $request->inputName . '</li>';
$body .= strlen($request->inputPhone) ?
                    '	<li>' . $request->inputPhone . '</li>'
                    : '';
$body .= '	<li>' . $request->inputEmail . '</li>';
$body .= '</ul>';

$body .= '<strong>New Student Info</strong>';
$body .= '<table>';
$body .= '	<thead>';
$body .= '		<tr>';

foreach (array('Name', 'Age', 'Level') as $hdg) {
    $body .= '			<th>' . $hdg . '</th>';
}

$body .= '		</tr>';
$body .= '	</thead>';

$body .= '<tbody>';

foreach ($children as $key => $child) {
    $body .= '<tr>';
    $body .= '	<td>';
    $body .= implode(
        '</td><td>',
        array(
            $child->Name,
            $child->Age,
            $child->Level,
        )
    );
    $body .= '	</td>';
    $body .= '</tr>';
}

$body .= '</tbody>';
$body .= '</table>';

$mailer->isHtml(true);
$mailer->Encoding = 'base64';

$mailer->addReplyTo($request->inputEmail, $request->inputName);
$mailer->setSubject($subject);
$mailer->setBody($body);

if ($mailer->Send()) {
    $out = json_encode(array(
        'status' => 'success',
        'heading' => 'Mail sent successfully!',
        'body' => 'Thank you for your inquiry!<br>We will be in touch very soon with more details.'
    ));
} else {
    $body = preg_replace('/<\/(tr|ul|li|p)>/', '%0D%0A', $body);
    $body = preg_replace('/<(br|table)>/', '%0D%0A', $body);
    $body = preg_replace('/<(strong|blockquote|ul)>/', '%0D%0A%0D%0A', $body);
    $body = str_replace('</td>', '%09%09', $body);
    $body = str_replace('</th>', '%09%09', $body);
    $body = strip_tags($body);

    $out = json_encode(array(
        'status' => 'error',
        'heading' => 'Sorry, there was an error sending the email.',
        'body' => 'Please email us directly using <a href="mailto: '
            . $to
            . '?subject=' . $subject
            . '&body=' . $body
            . '">this link</a>'
    ));
}

outputJSON($out);

function outputJSON($out)
{
    header('Content-type: application/json; charset="UTF-8"');
    header('Content-Length: ' . strlen($out));
    header('Connection: close');
    echo $out;
    flush();
}

function isValidRequest($request)
{
    return (
        isset($request->{'g-recaptcha-response'})
        && verifyReCaptcha($request->{'g-recaptcha-response'})
        && isset($request->inputName)
        && strlen($request->inputName) > 0
        && isset($request->inputEmail)
        && strlen($request->inputEmail) > 0
    );
}

function verifyReCaptcha($recaptchaCode)
{
    global $config;

    $postdata = http_build_query(
        [
        'secret'=> $config->get('captcha_secret'),
        'response'=> $recaptchaCode]
    );
    $opts = ['http' =>
        [
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        ]
    ];
    $context  = stream_context_create($opts);
    $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $check = json_decode($result);
    
    return $check->success;
}
