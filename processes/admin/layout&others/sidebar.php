<?php 
echo '
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #343a40;
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    }

    .sidebar a {
        display: flex;
        align-items: center;
        color: white;
        padding: 12px 20px;
        text-decoration: none;
        font-size: 16px;
        transition: 0.3s;
        border-left: 4px solid transparent;
    }

    .sidebar a i {
        margin-right: 12px;
        font-size: 18px;
    }

    .sidebar a:hover {
        background-color: #495057;
        border-left: 4px solid #17a2b8;
    }

    .main-content {
        flex-grow: 1;
        padding: 20px;
        margin-left: 250px; /* Ensures main content is pushed to the right */
    }
</style>

<div class="sidebar">
    <a href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="transactions.php"><i class="fas fa-exchange-alt"></i> Transactions</a>
    <a href="payments.php"><i class="fas fa-credit-card"></i> Payments</a>
    <a href="properties.php"><i class="fas fa-building"></i> Properties</a>
    <a href="add_property.php"><i class="fas fa-plus-circle"></i> Add Property</a>
    <a href="edit_property.php"><i class="fas fa-edit"></i> Edit Property</a>
    <a href="admin_logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>
';
?>
