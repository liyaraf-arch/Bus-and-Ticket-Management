<?php
// pages/login.php
require_once '../includes/config.php';

// Redirect if already logged in
if(isLoggedIn()) {
    redirect('index.php');
}

$page_title = __('login');

// Get redirect parameters
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
$route_id = isset($_GET['route_id']) ? $_GET['route_id'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang == 'bn' ? 'bn' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <style>
        
        /* Login Page Styles */
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            font-family: 'Source Sans Pro', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        form {
            background: white;
            border-radius: 30px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .svgContainer {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .mySVG {
            width: 180px;
            height: 180px;
        }
        
        .inputGroup {
            margin-bottom: 25px;
            position: relative;
        }
        
        .inputGroup label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .inputGroup input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .inputGroup input:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }
        
        .helper {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }
        
        #showPasswordToggle {
            position: absolute;
            right: 15px;
            top: 42px;
            cursor: pointer;
            font-size: 14px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        #showPasswordToggle input {
            width: auto;
            margin: 0;
        }
        
        button {
            width: 100%;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .register-link a {
            color: #27ae60;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .social-login {
            margin-top: 25px;
            text-align: center;
        }
        
        .social-login p {
            color: #999;
            margin-bottom: 15px;
            position: relative;
        }
        
        .social-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .social-btn {
            flex: 1;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            border-color: #27ae60;
            transform: translateY(-2px);
        }
        
    </style>
</head>
<body>

<form action="../process-login.php" method="POST">
    <!-- Redirect parameters -->
    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
    <input type="hidden" name="route_id" value="<?php echo $route_id; ?>">
    <input type="hidden" name="date" value="<?php echo $date; ?>">
    
    <div class="svgContainer">
        <div>
            <svg class="mySVG" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 0 200 200">
                <defs>
                    <circle id="armMaskPath" cx="100" cy="100" r="100" />
                </defs>
                <clipPath id="armMask">
                    <use xlink:href="#armMaskPath" overflow="visible" />
                </clipPath>
                <circle cx="100" cy="100" r="100" fill="#a9ddf3" />
                <g class="body">
                    <path class="bodyBGchanged" style="display: none;" fill="#FFFFFF"
                        d="M200,122h-35h-14.9V72c0-27.6-22.4-50-50-50s-50,22.4-50,50v50H35.8H0l0,91h200L200,122z" />
                    <path class="bodyBGnormal" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoinn="round" fill="#FFFFFF"
                        d="M200,158.5c0-20.2-14.8-36.5-35-36.5h-14.9V72.8c0-27.4-21.7-50.4-49.1-50.8c-28-0.5-50.9,22.1-50.9,50v50 H35.8C16,122,0,138,0,157.8L0,213h200L200,158.5z" />
                    <path fill="#DDF1FA"
                        d="M100,156.4c-22.9,0-43,11.1-54.1,27.7c15.6,10,34.2,15.9,54.1,15.9s38.5-5.8,54.1-15.9 C143,167.5,122.9,156.4,100,156.4z" />
                </g>
                <g class="earL">
                    <g class="outerEar" fill="#ddf1fa" stroke="#3a5e77" stroke-width="2.5">
                        <circle cx="47" cy="83" r="11.5" />
                        <path d="M46.3 78.9c-2.3 0-4.1 1.9-4.1 4.1 0 2.3 1.9 4.1 4.1 4.1" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </g>
                    <g class="earHair">
                        <rect x="51" y="64" fill="#FFFFFF" width="15" height="35" />
                        <path
                            d="M53.4 62.8C48.5 67.4 45 72.2 42.8 77c3.4-.1 6.8-.1 10.1.1-4 3.7-6.8 7.6-8.2 11.6 2.1 0 4.2 0 6.3.2-2.6 4.1-3.8 8.3-3.7 12.5 1.2-.7 3.4-1.4 5.2-1.9"
                            fill="#fff" stroke="#3a5e77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                </g>
                <g class="earR">
                    <g class="outerEar">
                        <circle fill="#DDF1FA" stroke="#3A5E77" stroke-width="2.5" cx="153" cy="83" r="11.5" />
                        <path fill="#DDF1FA" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            d="M153.7,78.9 c2.3,0,4.1,1.9,4.1,4.1c0,2.3-1.9,4.1-4.1,4.1" />
                    </g>
                    <g class="earHair">
                        <rect x="134" y="64" fill="#FFFFFF" width="15" height="35" />
                        <path fill="#FFFFFF" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            d="M146.6,62.8 c4.9,4.6,8.4,9.4,10.6,14.2c-3.4-0.1-6.8-0.1-10.1,0.1c4,3.7,6.8,7.6,8.2,11.6c-2.1,0-4.2,0-6.3,0.2c2.6,4.1,3.8,8.3,3.7,12.5 c-1.2-0.7-3.4-1.4-5.2-1.9" />
                    </g>
                </g>
                <path class="chin"
                    d="M84.1 121.6c2.7 2.9 6.1 5.4 9.8 7.5l.9-4.5c2.9 2.5 6.3 4.8 10.2 6.5 0-1.9-.1-3.9-.2-5.8 3 1.2 6.2 2 9.7 2.5-.3-2.1-.7-4.1-1.2-6.1"
                    fill="none" stroke="#3a5e77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                <path class="face" fill="#DDF1FA"
                    d="M134.5,46v35.5c0,21.815-15.446,39.5-34.5,39.5s-34.5-17.685-34.5-39.5V46" />
                <path class="hair" fill="#FFFFFF" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M81.457,27.929 c1.755-4.084,5.51-8.262,11.253-11.77c0.979,2.565,1.883,5.14,2.712,7.723c3.162-4.265,8.626-8.27,16.272-11.235 c-0.737,3.293-1.588,6.573-2.554,9.837c4.857-2.116,11.049-3.64,18.428-4.156c-2.403,3.23-5.021,6.391-7.852,9.474" />
                <g class="eyebrow">
                    <path fill="#FFFFFF"
                        d="M138.142,55.064c-4.93,1.259-9.874,2.118-14.787,2.599c-0.336,3.341-0.776,6.689-1.322,10.037 c-4.569-1.465-8.909-3.222-12.996-5.226c-0.98,3.075-2.07,6.137-3.267,9.179c-5.514-3.067-10.559-6.545-15.097-10.329 c-1.806,2.889-3.745,5.73-5.816,8.515c-7.916-4.124-15.053-9.114-21.296-14.738l1.107-11.768h73.475V55.064z" />
                    <path fill="#FFFFFF" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        d="M63.56,55.102 c6.243,5.624,13.38,10.614,21.296,14.738c2.071-2.785,4.01-5.626,5.816-8.515c4.537,3.785,9.583,7.263,15.097,10.329 c1.197-3.043,2.287-6.104,3.267-9.179c4.087,2.004,8.427,3.761,12.996,5.226c0.545-3.348,0.986-6.696,1.322-10.037 c4.913-0.481,9.857-1.34,14.787-2.599" />
                </g>
                <g class="eyeL">
                    <circle cx="85.5" cy="78.5" r="3.5" fill="#3a5e77" />
                    <circle cx="84" cy="76" r="1" fill="#fff" />
                </g>
                <g class="eyeR">
                    <circle cx="114.5" cy="78.5" r="3.5" fill="#3a5e77" />
                    <circle cx="113" cy="76" r="1" fill="#fff" />
                </g>
                <g class="mouth">
                    <path class="mouthBG" fill="#617E92"
                        d="M100.2,101c-0.4,0-1.4,0-1.8,0c-2.7-0.3-5.3-1.1-8-2.5c-0.7-0.3-0.9-1.2-0.6-1.8 c0.2-0.5,0.7-0.7,1.2-0.7c0.2,0,0.5,0.1,0.6,0.2c3,1.5,5.8,2.3,8.6,2.3s5.7-0.7,8.6-2.3c0.2-0.1,0.4-0.2,0.6-0.2 c0.5,0,1,0.3,1.2,0.7c0.4,0.7,0.1,1.5-0.6,1.9c-2.6,1.4-5.3,2.2-7.9,2.5C101.7,101,100.5,101,100.2,101z" />
                    <path style="display: none;" class="mouthSmallBG" fill="#617E92"
                        d="M100.2,101c-0.4,0-1.4,0-1.8,0c-2.7-0.3-5.3-1.1-8-2.5c-0.7-0.3-0.9-1.2-0.6-1.8 c0.2-0.5,0.7-0.7,1.2-0.7c0.2,0,0.5,0.1,0.6,0.2c3,1.5,5.8,2.3,8.6,2.3s5.7-0.7,8.6-2.3c0.2-0.1,0.4-0.2,0.6-0.2 c0.5,0,1,0.3,1.2,0.7c0.4,0.7,0.1,1.5-0.6,1.9c-2.6,1.4-5.3,2.2-7.9,2.5C101.7,101,100.5,101,100.2,101z" />
                    <path class="mouthOutline" fill="none" stroke="#3A5E77" stroke-width="2.5" stroke-linejoin="round"
                        d="M100.2,101c-0.4,0-1.4,0-1.8,0c-2.7-0.3-5.3-1.1-8-2.5c-0.7-0.3-0.9-1.2-0.6-1.8 c0.2-0.5,0.7-0.7,1.2-0.7c0.2,0,0.5,0.1,0.6,0.2c3,1.5,5.8,2.3,8.6,2.3s5.7-0.7,8.6-2.3c0.2-0.1,0.4-0.2,0.6-0.2 c0.5,0,1,0.3,1.2,0.7c0.4,0.7,0.1,1.5-0.6,1.9c-2.6,1.4-5.3,2.2-7.9,2.5C101.7,101,100.5,101,100.2,101z" />
                </g>
                <path class="nose"
                    d="M97.7 79.9h4.7c1.9 0 3 2.2 1.9 3.7l-2.3 3.3c-.9 1.3-2.9 1.3-3.8 0l-2.3-3.3c-1.3-1.6-.2-3.7 1.8-3.7z"
                    fill="#3a5e77" />
                <g class="arms" clip-path="url(#armMask)">
                    <g class="armL" style="visibility: hidden;">
                        <polygon fill="#DDF1FA" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            stroke-miterlimit="10" points="121.3,98.4 111,59.7 149.8,49.3 169.8,85.4" />
                        <path fill="#DDF1FA" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            stroke-miterlimit="10"
                            d="M134.4,53.5l19.3-5.2c2.7-0.7,5.4,0.9,6.1,3.5v0c0.7,2.7-0.9,5.4-3.5,6.1l-10.3,2.8" />
                        <path fill="#DDF1FA" stroke="#3A5E77" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            stroke-miterlimit="10"
                            d="M150.9,59.4l26-7c2.7-0.7,5.4,0.9,6.1,3.5v0c0.7,2.7-0.9,5.4-3.5,6.1l-21.3,5.7" />
                    </g>
                    <g class="armR" style="visibility: hidden;">
                        <path fill="#ddf1fa" stroke="#3a5e77" stroke-linecap="round" stroke-linejoin="round"
                            stroke-miterlimit="10" stroke-width="2.5" d="M265.4 97.3l10.4-38.6-38.9-10.5-20 36.1z" />
                        <path fill="#ddf1fa" stroke="#3a5e77" stroke-linecap="round" stroke-linejoin="round"
                            stroke-miterlimit="10" stroke-width="2.5"
                            d="M252.4 52.4L233 47.2c-2.7-.7-5.4.9-6.1 3.5-.7 2.7.9 5.4 3.5 6.1l10.3 2.8M226 76.4l-19.4-5.2c-2.7-.7-5.4.9-6.1 3.5-.7 2.7.9 5.4 3.5 6.1l18.3 4.9M228.4 66.7l-23.1-6.2c-2.7-.7-5.4.9-6.1 3.5-.7 2.7.9 5.4 3.5 6.1l23.1 6.2M235.8 58.3l-26-7c-2.7-.7-5.4.9-6.1 3.5-.7 2.7.9 5.4 3.5 6.1l21.3 5.7" />
                    </g>
                </g>
            </svg>
        </div>
    </div>
    <p style="opacity: 60%; text-align: center; margin-bottom: 14px;" >Please login with Your Email & Password</p>

    <?php displayMessage(); ?>

    <div class="inputGroup inputGroup1">
        <label for="loginEmail" id="loginEmailLabel"><?php echo __('Email'); ?></label>
        <input type="email" id="loginEmail" name="email" maxlength="254" required placeholder="<?php echo __('Enter your email'); ?>" />
    </div>
    
    <div class="inputGroup inputGroup2">
        <label for="loginPassword" id="loginPasswordLabel"><?php echo __('Password'); ?></label>
        <input type="password" id="loginPassword" name="password" required />
        <label id="showPasswordToggle" for="showPasswordCheck">
            <input id="showPasswordCheck" type="checkbox" /> Show
        </label>
    </div>
    
    <div class="inputGroup inputGroup3">
        <button type="submit" id="login"><?php echo __('login'); ?></button>
    </div>
    
    <div class="register-link">
        <?php echo __("Don't have account"); ?> 
        <a href="register.php"><?php echo __('Register'); ?></a>
    </div>
    
    <div class="social-login">
        <p><?php echo __('or login with'); ?></p>
        <div class="social-buttons">
            <a href="#" class="social-btn">
                <i class="fab fa-google"></i> Google
            </a>
            <a href="#" class="social-btn">
                <i class="fab fa-facebook"></i> Facebook
            </a>
        </div>
    </div>
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
<script>
    // ========== এখানে আপনার JavaScript কোড বসান ==========
    
    var emailLabel = document.querySelector('#loginEmailLabel'), 
        email = document.querySelector('#loginEmail'), 
        passwordLabel = document.querySelector('#loginPasswordLabel'), 
        password = document.querySelector('#loginPassword'), 
        showPasswordCheck = document.querySelector('#showPasswordCheck'), 
        showPasswordToggle = document.querySelector('#showPasswordToggle'), 
        mySVG = document.querySelector('.svgContainer'), 
        twoFingers = document.querySelector('.twoFingers'), 
        armL = document.querySelector('.armL'), 
        armR = document.querySelector('.armR'), 
        eyeL = document.querySelector('.eyeL'), 
        eyeR = document.querySelector('.eyeR'), 
        nose = document.querySelector('.nose'), 
        mouth = document.querySelector('.mouth'), 
        mouthBG = document.querySelector('.mouthBG'), 
        mouthSmallBG = document.querySelector('.mouthSmallBG'), 
        mouthMediumBG = document.querySelector('.mouthMediumBG'), 
        mouthLargeBG = document.querySelector('.mouthLargeBG'), 
        mouthMaskPath = document.querySelector('#mouthMaskPath'), 
        mouthOutline = document.querySelector('.mouthOutline'), 
        tooth = document.querySelector('.tooth'), 
        tongue = document.querySelector('.tongue'), 
        chin = document.querySelector('.chin'), 
        face = document.querySelector('.face'), 
        eyebrow = document.querySelector('.eyebrow'), 
        outerEarL = document.querySelector('.earL .outerEar'), 
        outerEarR = document.querySelector('.earR .outerEar'), 
        earHairL = document.querySelector('.earL .earHair'), 
        earHairR = document.querySelector('.earR .earHair'), 
        hair = document.querySelector('.hair'), 
        bodyBG = document.querySelector('.bodyBGnormal'), 
        bodyBGchanged = document.querySelector('.bodyBGchanged');
    
    var activeElement, curEmailIndex, screenCenter, svgCoords, emailCoords, emailScrollMax, chinMin = .5, 
        dFromC, mouthStatus = "small", blinking, eyeScale = 1, eyesCovered = false, showPasswordClicked = false;
    var eyeLCoords, eyeRCoords, noseCoords, mouthCoords, eyeLAngle, eyeLX, eyeLY, eyeRAngle, eyeRX, eyeRY, 
        noseAngle, noseX, noseY, mouthAngle, mouthX, mouthY, mouthR, chinX, chinY, chinS, faceX, faceY, 
        faceSkew, eyebrowSkew, outerEarX, outerEarY, hairX, hairS;

    function calculateFaceMove(e) {
        var carPos = email.selectionEnd,
            div = document.createElement('div'),
            span = document.createElement('span'),
            copyStyle = getComputedStyle(email),
            caretCoords = {};
        
        if (carPos == null || carPos == 0) {
            carPos = email.value.length;
        }
        [].forEach.call(copyStyle, function (prop) {
            div.style[prop] = copyStyle[prop];
        });
        div.style.position = 'absolute';
        document.body.appendChild(div);
        div.textContent = email.value.substr(0, carPos);
        span.textContent = email.value.substr(carPos) || '.';
        div.appendChild(span);

        if (email.scrollWidth <= emailScrollMax) {
            caretCoords = getPosition(span);
            dFromC = screenCenter - (caretCoords.x + emailCoords.x);
            eyeLAngle = getAngle(eyeLCoords.x, eyeLCoords.y, emailCoords.x + caretCoords.x, emailCoords.y + 25);
            eyeRAngle = getAngle(eyeRCoords.x, eyeRCoords.y, emailCoords.x + caretCoords.x, emailCoords.y + 25);
            noseAngle = getAngle(noseCoords.x, noseCoords.y, emailCoords.x + caretCoords.x, emailCoords.y + 25);
            mouthAngle = getAngle(mouthCoords.x, mouthCoords.y, emailCoords.x + caretCoords.x, emailCoords.y + 25);
        } else {
            eyeLAngle = getAngle(eyeLCoords.x, eyeLCoords.y, emailCoords.x + emailScrollMax, emailCoords.y + 25);
            eyeRAngle = getAngle(eyeRCoords.x, eyeRCoords.y, emailCoords.x + emailScrollMax, emailCoords.y + 25);
            noseAngle = getAngle(noseCoords.x, noseCoords.y, emailCoords.x + emailScrollMax, emailCoords.y + 25);
            mouthAngle = getAngle(mouthCoords.x, mouthCoords.y, emailCoords.x + emailScrollMax, emailCoords.y + 25);
        }

        eyeLX = Math.cos(eyeLAngle) * 20;
        eyeLY = Math.sin(eyeLAngle) * 10;
        eyeRX = Math.cos(eyeRAngle) * 20;
        eyeRY = Math.sin(eyeRAngle) * 10;
        noseX = Math.cos(noseAngle) * 23;
        noseY = Math.sin(noseAngle) * 10;
        mouthX = Math.cos(mouthAngle) * 23;
        mouthY = Math.sin(mouthAngle) * 10;
        mouthR = Math.cos(mouthAngle) * 6;
        chinX = mouthX * .8;
        chinY = mouthY * .5;
        chinS = 1 - ((dFromC * .15) / 100);
        if (chinS > 1) {
            chinS = 1 - (chinS - 1);
            if (chinS < chinMin) {
                chinS = chinMin;
            }
        }
        faceX = mouthX * .3;
        faceY = mouthY * .4;
        faceSkew = Math.cos(mouthAngle) * 5;
        eyebrowSkew = Math.cos(mouthAngle) * 25;
        outerEarX = Math.cos(mouthAngle) * 4;
        outerEarY = Math.cos(mouthAngle) * 5;
        hairX = Math.cos(mouthAngle) * 6;
        hairS = 1.2;

        TweenMax.to(eyeL, 1, { x: -eyeLX, y: -eyeLY, ease: Expo.easeOut });
        TweenMax.to(eyeR, 1, { x: -eyeRX, y: -eyeRY, ease: Expo.easeOut });
        TweenMax.to(nose, 1, { x: -noseX, y: -noseY, rotation: mouthR, transformOrigin: "center center", ease: Expo.easeOut });
        TweenMax.to(mouth, 1, { x: -mouthX, y: -mouthY, rotation: mouthR, transformOrigin: "center center", ease: Expo.easeOut });
        TweenMax.to(chin, 1, { x: -chinX, y: -chinY, scaleY: chinS, ease: Expo.easeOut });
        TweenMax.to(face, 1, { x: -faceX, y: -faceY, skewX: -faceSkew, transformOrigin: "center top", ease: Expo.easeOut });
        TweenMax.to(eyebrow, 1, { x: -faceX, y: -faceY, skewX: -eyebrowSkew, transformOrigin: "center top", ease: Expo.easeOut });
        TweenMax.to(outerEarL, 1, { x: outerEarX, y: -outerEarY, ease: Expo.easeOut });
        TweenMax.to(outerEarR, 1, { x: outerEarX, y: outerEarY, ease: Expo.easeOut });
        TweenMax.to(earHairL, 1, { x: -outerEarX, y: -outerEarY, ease: Expo.easeOut });
        TweenMax.to(earHairR, 1, { x: -outerEarX, y: outerEarY, ease: Expo.easeOut });
        TweenMax.to(hair, 1, { x: hairX, scaleY: hairS, transformOrigin: "center bottom", ease: Expo.easeOut });

        document.body.removeChild(div);
    }

    function onEmailInput(e) {
        calculateFaceMove(e);
        var value = email.value;
        curEmailIndex = value.length;

        if (curEmailIndex > 0) {
            if (mouthStatus == "small") {
                mouthStatus = "medium";
                TweenMax.to([mouthBG, mouthOutline, mouthMaskPath], 1, { morphSVG: mouthMediumBG, shapeIndex: 8, ease: Expo.easeOut });
                TweenMax.to(tooth, 1, { x: 0, y: 0, ease: Expo.easeOut });
                TweenMax.to(tongue, 1, { x: 0, y: 1, ease: Expo.easeOut });
                TweenMax.to([eyeL, eyeR], 1, { scaleX: .85, scaleY: .85, ease: Expo.easeOut });
                eyeScale = .85;
            }
            if (value.includes("@")) {
                mouthStatus = "large";
                TweenMax.to([mouthBG, mouthOutline, mouthMaskPath], 1, { morphSVG: mouthLargeBG, ease: Expo.easeOut });
                TweenMax.to(tooth, 1, { x: 3, y: -2, ease: Expo.easeOut });
                TweenMax.to(tongue, 1, { y: 2, ease: Expo.easeOut });
                TweenMax.to([eyeL, eyeR], 1, { scaleX: .65, scaleY: .65, ease: Expo.easeOut, transformOrigin: "center center" });
                eyeScale = .65;
            } else {
                mouthStatus = "medium";
                TweenMax.to([mouthBG, mouthOutline, mouthMaskPath], 1, { morphSVG: mouthMediumBG, ease: Expo.easeOut });
                TweenMax.to(tooth, 1, { x: 0, y: 0, ease: Expo.easeOut });
                TweenMax.to(tongue, 1, { x: 0, y: 1, ease: Expo.easeOut });
                TweenMax.to([eyeL, eyeR], 1, { scaleX: .85, scaleY: .85, ease: Expo.easeOut });
                eyeScale = .85;
            }
        } else {
            mouthStatus = "small";
            TweenMax.to([mouthBG, mouthOutline, mouthMaskPath], 1, { morphSVG: mouthSmallBG, shapeIndex: 9, ease: Expo.easeOut });
            TweenMax.to(tooth, 1, { x: 0, y: 0, ease: Expo.easeOut });
            TweenMax.to(tongue, 1, { y: 0, ease: Expo.easeOut });
            TweenMax.to([eyeL, eyeR], 1, { scaleX: 1, scaleY: 1, ease: Expo.easeOut });
            eyeScale = 1;
        }
    }

    function onEmailFocus(e) {
        activeElement = "email";
        e.target.parentElement.classList.add("focusWithText");
        onEmailInput();
    }

    function onEmailBlur(e) {
        activeElement = null;
        setTimeout(function () {
            if (activeElement == "email") {
            } else {
                if (e.target.value == "") {
                    e.target.parentElement.classList.remove("focusWithText");
                }
                resetFace();
            }
        }, 100);
    }

    function onEmailLabelClick(e) {
        activeElement = "email";
    }

    function onPasswordFocus(e) {
        activeElement = "password";
        if (!eyesCovered) {
            coverEyes();
        }
    }

    function onPasswordBlur(e) {
        activeElement = null;
        setTimeout(function () {
            if (activeElement == "toggle" || activeElement == "password") {
            } else {
                uncoverEyes();
            }
        }, 100);
    }

    function onPasswordToggleFocus(e) {
        activeElement = "toggle";
        if (!eyesCovered) {
            coverEyes();
        }
    }

    function onPasswordToggleBlur(e) {
        activeElement = null;
        if (!showPasswordClicked) {
            setTimeout(function () {
                if (activeElement == "password" || activeElement == "toggle") {
                } else {
                    uncoverEyes();
                }
            }, 100);
        }
    }

    function onPasswordToggleMouseDown(e) {
        showPasswordClicked = true;
    }

    function onPasswordToggleMouseUp(e) {
        showPasswordClicked = false;
    }

    function onPasswordToggleChange(e) {
        setTimeout(function () {
            if (e.target.checked) {
                password.type = "text";
                spreadFingers();
            } else {
                password.type = "password";
                closeFingers();
            }
        }, 100);
    }

    function onPasswordToggleClick(e) {
        e.target.focus();
    }

    function spreadFingers() {
        TweenMax.to(twoFingers, .35, { transformOrigin: "bottom left", rotation: 30, x: -9, y: -2, ease: Power2.easeInOut });
    }

    function closeFingers() {
        TweenMax.to(twoFingers, .35, { transformOrigin: "bottom left", rotation: 0, x: 0, y: 0, ease: Power2.easeInOut });
    }

    function coverEyes() {
        TweenMax.killTweensOf([armL, armR]);
        TweenMax.set([armL, armR], { visibility: "visible" });
        TweenMax.to(armL, .45, { x: -93, y: 10, rotation: 0, ease: Quad.easeOut });
        TweenMax.to(armR, .45, { x: -93, y: 10, rotation: 0, ease: Quad.easeOut, delay: .1 });
        TweenMax.to(bodyBG, .45, { morphSVG: bodyBGchanged, ease: Quad.easeOut });
        eyesCovered = true;
    }

    function uncoverEyes() {
        TweenMax.killTweensOf([armL, armR]);
        TweenMax.to(armL, 1.35, { y: 220, ease: Quad.easeOut });
        TweenMax.to(armL, 1.35, { rotation: 105, ease: Quad.easeOut, delay: .1 });
        TweenMax.to(armR, 1.35, { y: 220, ease: Quad.easeOut });
        TweenMax.to(armR, 1.35, {
            rotation: -105, ease: Quad.easeOut, delay: .1, onComplete: function () {
                TweenMax.set([armL, armR], { visibility: "hidden" });
            }
        });
        TweenMax.to(bodyBG, .45, { morphSVG: bodyBG, ease: Quad.easeOut });
        eyesCovered = false;
    }

    function resetFace() {
        TweenMax.to([eyeL, eyeR], 1, { x: 0, y: 0, ease: Expo.easeOut });
        TweenMax.to(nose, 1, { x: 0, y: 0, scaleX: 1, scaleY: 1, ease: Expo.easeOut });
        TweenMax.to(mouth, 1, { x: 0, y: 0, rotation: 0, ease: Expo.easeOut });
        TweenMax.to(chin, 1, { x: 0, y: 0, scaleY: 1, ease: Expo.easeOut });
        TweenMax.to([face, eyebrow], 1, { x: 0, y: 0, skewX: 0, ease: Expo.easeOut });
        TweenMax.to([outerEarL, outerEarR, earHairL, earHairR, hair], 1, { x: 0, y: 0, scaleY: 1, ease: Expo.easeOut });
    }

    function startBlinking(delay) {
        if (delay) {
            delay = getRandomInt(delay);
        } else {
            delay = 1;
        }
        blinking = TweenMax.to([eyeL, eyeR], .1, {
            delay: delay, scaleY: 0, yoyo: true, repeat: 1, transformOrigin: "center center", onComplete: function () {
                startBlinking(12);
            }
        });
    }

    function stopBlinking() {
        blinking.kill();
        blinking = null;
        TweenMax.set([eyeL, eyeR], { scaleY: eyeScale });
    }

    function getRandomInt(max) {
        return Math.floor(Math.random() * Math.floor(max));
    }

    function getAngle(x1, y1, x2, y2) {
        var angle = Math.atan2(y1 - y2, x1 - x2);
        return angle;
    }

    function getPosition(el) {
        var xPos = 0;
        var yPos = 0;

        while (el) {
            if (el.tagName == "BODY") {
                var xScroll = el.scrollLeft || document.documentElement.scrollLeft;
                var yScroll = el.scrollTop || document.documentElement.scrollTop;
                xPos += (el.offsetLeft - xScroll + el.clientLeft);
                yPos += (el.offsetTop - yScroll + el.clientTop);
            } else {
                xPos += (el.offsetLeft - el.scrollLeft + el.clientLeft);
                yPos += (el.offsetTop - el.scrollTop + el.clientTop);
            }
            el = el.offsetParent;
        }
        return {
            x: xPos,
            y: yPos
        };
    }

    function isMobileDevice() {
        var check = false;
        (function (a) { if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true; })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    }

    function initLoginForm() {
        svgCoords = getPosition(mySVG);
        emailCoords = getPosition(email);
        screenCenter = svgCoords.x + (mySVG.offsetWidth / 2);
        eyeLCoords = { x: svgCoords.x + 84, y: svgCoords.y + 76 };
        eyeRCoords = { x: svgCoords.x + 113, y: svgCoords.y + 76 };
        noseCoords = { x: svgCoords.x + 97, y: svgCoords.y + 81 };
        mouthCoords = { x: svgCoords.x + 100, y: svgCoords.y + 100 };

        email.addEventListener('focus', onEmailFocus);
        email.addEventListener('blur', onEmailBlur);
        email.addEventListener('input', onEmailInput);
        emailLabel.addEventListener('click', onEmailLabelClick);

        password.addEventListener('focus', onPasswordFocus);
        password.addEventListener('blur', onPasswordBlur);

        showPasswordCheck.addEventListener('change', onPasswordToggleChange);
        showPasswordCheck.addEventListener('focus', onPasswordToggleFocus);
        showPasswordCheck.addEventListener('blur', onPasswordToggleBlur);
        showPasswordCheck.addEventListener('click', onPasswordToggleClick);
        showPasswordToggle.addEventListener('mouseup', onPasswordToggleMouseUp);
        showPasswordToggle.addEventListener('mousedown', onPasswordToggleMouseDown);

        TweenMax.set(armL, { x: -93, y: 220, rotation: 105, transformOrigin: "top left" });
        TweenMax.set(armR, { x: -93, y: 220, rotation: -105, transformOrigin: "top right" });
        TweenMax.set(mouth, { transformOrigin: "center center" });

        startBlinking(5);
        emailScrollMax = email.scrollWidth;

        if (isMobileDevice()) {
            password.type = "text";
            showPasswordCheck.checked = true;
            TweenMax.set(twoFingers, { transformOrigin: "bottom left", rotation: 30, x: -9, y: -2, ease: Power2.easeInOut });
        }

        console.clear();
    }

    initLoginForm();
</script>

</body>
</html>