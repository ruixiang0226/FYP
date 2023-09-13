<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Waiting Room</title>
        <link rel="stylesheet" type="text/css" href="/web/assets/css/waiting_room.css">
        <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body>
        <section class="container">
            <header>
                <div class="logo_container">
                    <img src="/web/assets/img/logo.png" alt="Logo">
                    <span>Penang Local Food</span>
                </div>
                    <div class="icon" id="header_logout">
                        <a class="icon_link" href="/index.html">
                            <i class='bx bx-home'></i>
                            <span class="icon_label">Home</span>
                        </a>
                    </div>
                </div>
            </header>
            <section class="wrapper">
                <div class="waiting_container">
                    <div class="waiting_wrapper">
                        <i class="uil uil-process"></i>
                        <h1>Pending</h1>
                        <p>Thank you for your application. Please wait for the administrator to review and complete your information. After the review is passed, you can use our website!</p>
                    </div>
                <div class="provide">
                        <label>You can provide your email, after approve with notified you.</label>
                        <div class="submit">
                            <form action="email.php" method="POST">
                            <input type="text" name="address" placeholder="Your email address">
                            <button type="submit">Submit</button>
                        </form>
                        </div>
                    </div>
                </div>

            </section>
            
        </section>
    </body>
</html>