<?php
$conn = new mysqli('ilzyz0heng1bygi8.chr7pe7iynqr.eu-west-1.rds.amazonaws.com', 'wdzd5d37qxl2zori', 'gnvgq0h5y6vmdhqr', 'p40t91itwyub22ct');

$sql = "SELECT vendor_id, vendor_name, food_type, address, phone_number, dining_option, status FROM vendorpages WHERE status='pending'";
$result = $conn->query($sql);

$pending_applications = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $pending_applications [] = $row;
    }
}

$sql1 = "SELECT id, username FROM vendor";
$result1 = $conn->query($sql1);
$vendors = $result1->fetch_all(MYSQLI_ASSOC);

foreach ($pending_applications as &$application) {
    foreach ($vendors as $vendor) {
        if ($application['vendor_id'] == $vendor['id']) {
            $application['username'] = $vendor['username'];
            break;
        }
    }
}

$sql2 = "SELECT id, username, phone, email, vendor_name, location, status FROM vendor WHERE status='pending'";
$result2 = $conn->query($sql2);

$vendor_account_applys = array();

if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
        $vendor_account_applys  [] = $row;
    }
}

$sql3 = "SELECT id, username, email FROM user";
$result3 = $conn->query($sql3);

$user_accounts = array();

if ($result3->num_rows > 0) {
    while($row = $result3->fetch_assoc()) {
        $user_accounts  [] = $row;
    }
}

$sql4 = "SELECT id, username, phone, email, vendor_name, location FROM vendor WHERE  status='approved'";
$result4 = $conn->query($sql4);

$vendor_accounts = array();

if ($result4->num_rows > 0) {
    while($row = $result4->fetch_assoc()) {
        $vendor_accounts  [] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="/web/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">  
</head>
<body>
    <section class="container">
        <header>
            <div class="logo_container">
                <i class='bx bxs-dashboard bx-rotate-270' ></i>
                <span>Dashboard</span>
                <i class='icn menuicn bx bx-menu' id="menuicn"></i>
            </div>
            <div class="search">
                <div class="search_container">
                    <div class="search_box">
                        <div class="search_box_icon">
                            <i class="bx bx-search"></i>
                        </div>
                        <div class="search_input">
                            <input type="search" autocomplete="off" placeholder="Search id, username, vendorname..." value>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header_icons">
                <div class="icon" id="header_bth">
                    <a class="icon_link" id="homepage_link" href="/admin/admin.php">
                        <i class='bx bx-home' ></i>
                        <span class="icon_label">Home</span>
                    </a>
                </div>
                <div class="icon" id="header_logout">
                    <a class="icon_link" href="">
                        <i class='bx bx-log-out'></i>
                        <span class="icon_label">Logout</span>
                    </a>
                </div>
            </div>
        </header>
    </section> 
    
    <section class="wrapper">
        <div class="nav_container">
            <div class="nav_wrapper">
                <div class="nav-option" id="nav_container">
                    <div class="nav option1" id="dashboardNav">
                        <i class='bx bxs-bell'></i>
                        <h2>Application</h2>
                    </div>
                    <div class="nav option" id="vendorApplyNav">
                        <i class='bx bxs-user-plus' ></i>
                        <h2>Vendor Apply</h2>
                    </div>
                    <div class="nav option" id="userAccountNav">
                        <i class='bx bxs-user'></i>
                        <h2>User Account</h2>
                    </div>
                    <div class="nav optionlast" id="vendorAccountNav">
                        <i class='bx bxs-user-circle' ></i>
                        <h2>Vendor Account</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-container">
            <div class="main">
                <div id="dashboardContent" class="content-section active">
                    <div class="application_container">
                        <div class="application_header">
                            <h1>Vendorpage Application</h1>
                        </div>
                        
                        <div class="application_body">
                            <div class="dashboard_heading">
                                <div class="column first">
                                    <h4>Id</h4>
                                </div>
                                <div class="column">
                                    <h4>Username</h4>
                                </div>
                                <div class="column">
                                    <h4>Vendor Name</h4>
                                </div>
                                <div class="column">
                                    <h4>Food Type</h4>
                                </div>
                                <div class="column">
                                    <h4>Address</h4>
                                </div>
                                <div class="column">
                                    <h4>Phone Number</h4>
                                </div>
                                <div class="column">
                                    <h4>Dining Option</h4>
                                </div>
                                <div class="column">
                                    <h4>Status</h4>
                                </div>
                                <div class="column">
                                    <h4>Decision</h4>
                                </div>
                            </div>
                            
                            <?php 
                            $counter = 1;
                            foreach ($pending_applications as &$application): ?>
                                <div class="vendor" id="vendorpage_<?php echo htmlspecialchars($application['vendor_id']); ?>">
                                    <div class="info_wrapper id_number">
                                        <div class="info_column"> 
                                            <p><?php echo $counter; ?></p>
                                        </div>
                                    </div>
                                    <div class="info_wrapper">
                                        <div class="info_column"> 
                                            <p><?php echo htmlspecialchars($application['username']); ?></p>
                                        </div>
                                    </div>
                                    <div class="info_wrapper">
                                        <div class="info_column"> 
                                            <p><?php echo htmlspecialchars($application['vendor_name']); ?></p>
                                        </div>
                                    </div>
                                    <div class="info_wrapper">
                                        <div class="info_column"> 
                                            <p><?php echo htmlspecialchars($application['food_type']); ?></p>
                                        </div>
                                    </div>     
                                    <div class="info_wrapper">
                                        <div class="info_column"> 
                                            <p><?php echo htmlspecialchars($application['address']); ?></p>
                                        </div>
                                    </div>
                                    <div class="info_wrapper">
                                        <div class="info_column"> 
                                            <p><?php echo htmlspecialchars($application['phone_number']); ?></p>
                                        </div>
                                    </div>
                                    <div class="info_wrapper">    
                                        <div class="info_column"> 
                                            <p><?php echo htmlspecialchars($application['dining_option']); ?></p>
                                        </div>       
                                    </div>
                                    <div class="info_wrapper">
                                        <div class="info_column"> 
                                            <p><?php echo htmlspecialchars($application['status']); ?></p>
                                        </div>
                                    </div>
                                    <div class="info_wrapper decision last">
                                        <div class="info_column">
                                            <form method="post" action="/api/decision.php">
                                                <input type="hidden" name="vendor_id" value="<?php echo $application['vendor_id']; ?>">
                                                <button type="submit" class="approve" name="decision" value="approve">Approve</button>
                                                <button type="submit" class="reject" name="decision" value="reject">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php 
                                $counter++;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="vendorApplicationContent" class="content-section">
                        <div class="application_container">
                            <div class="application_header">
                                <h1>Vendor Account Application</h1>
                            </div>
                            
                            <div class="application_body">
                                <div class="dashboard_heading">
                                    <div class="column first">
                                        <h4>Id</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Username</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Vendor Name</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Address</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Phone Number</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Status</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Decision</h4>
                                    </div>
                                
                                </div>
                                
                                <?php 
                                $counter = 1;
                                foreach ($vendor_account_applys as $apply): ?>
                                    <div class="vendor" id="vendor_apply_<?php echo htmlspecialchars($apply['id']); ?>">
                                        <div class="info_wrapper id_number">
                                            <div class="info_column "> 
                                                <p><?php echo $counter; ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($apply['username']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">        
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($apply['vendor_name']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($apply['location']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($apply['phone']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">    
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($apply['status']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper decision last"> 
                                            <div class=" info_column">
                                                <form method="post" action="/api/decision_apply.php">
                                                    <input type="hidden" name="id" value="<?php echo $apply['id']; ?>">
                                                    <button type="submit" class="approve" name="decision_acc" value="approve">Approve</button>
                                                    <button type="submit" class="reject" name="decision_acc" value="reject">Reject</button>
                                                </form>
                                            </div>
                                        </div>
                                </div>

                                    <?php 
                                    $counter++;
                                    endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="userAccountContent" class="content-section">
                        <div class="application_container">
                            <div class="application_header">
                                <h1>User Account</h1>
                            </div>
                            
                            <div class="application_body">
                                <div class="dashboard_heading">
                                    <div class="column first">
                                        <h4>Id</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Username</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Email Address</h4>
                                    </div>                 
                                </div>
                                
                                <?php 
                                $counter = 1;
                                foreach ($user_accounts as $user): ?>
                                    <div class="vendor" id="user_<?php echo htmlspecialchars($user['id']); ?>">
                                        <div class="info_wrapper userId">
                                            <div class="info_column"> 
                                                <p><?php echo $counter; ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($user['username']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper last">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($user['email']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    $counter++;
                                    endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div id="vendorAccountContent" class="content-section">
                        <div class="application_container">
                            <div class="application_header">
                                <h1>Vendor Account</h1>
                            </div>
                            
                            <div class="application_body">
                                <div class="dashboard_heading">
                                    <div class="column first">
                                        <h4>Id</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Username</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Vendor Name</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Address</h4>
                                    </div>
                                    <div class="column">
                                        <h4>Phone Number</h4>
                                    </div>                 
                                </div>
                                
                                <?php 
                                $counter = 1;
                                foreach ($vendor_accounts as $vendor): ?>
                                    <div class="vendor" id="vendor_<?php echo htmlspecialchars($vendor['id']); ?>">
                                        <div class="info_wrapper id_number">
                                            <div class="info_column "> 
                                                <p><?php echo $counter; ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($vendor['username']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($vendor['vendor_name']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($vendor['location']); ?></p>
                                            </div>
                                        </div>
                                        <div class="info_wrapper last">
                                            <div class="info_column"> 
                                                <p><?php echo htmlspecialchars($vendor['phone']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                    $counter++;
                                    endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <script src="/web/assets/js/dashboard.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            let homepageLink = document.getElementById('homepage_link');
        
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(";").shift();
            }
        
            const userType = getCookie("user_type");
        
            if (userType !== "admin") {
                window.location.href = "/auth/view/login.html";
            }
        
            let vendorId = getCookie('admin_id');
        
            if (vendorId) {
                homepageLink.href = "/admin/admin.php";
            }

            logoutButton = document.getElementById("header_logout");
            
            logoutButton.addEventListener("click", function(event) {
                event.preventDefault();
                document.cookie = "admin_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "user_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                console.log(document.cookie);
                window.location.href = "/auth/view/login.html";
                fetch('/api/logout.php')
                .then(response => response.json())
                .then(data => {
                    window.location.href = '/auth/view/login.html';
                });
            });
        });
        </script>
</body>
</html>
