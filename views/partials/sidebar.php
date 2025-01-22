<style>
    strong{
        font-size: 12pt;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
</style>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-win"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            <img src="../../img/bbtma.png" alt="" style="width: 30%;">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <strong>Dashboard</strong></a>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <strong>Admin</strong>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="/adduser"><strong>Add User</strong></a>
                <a class="collapse-item" href="#"><strong>Manage Applicant</strong></a>
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="icofont-group-students"></i>
            <!-- <img src="../../img/bg3.png" alt="" style="width: 50px;"></i> -->
           <strong>Student</strong>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="/addstudent"><strong>Add Student</strong></a>
                <a class="collapse-item" href="/managstudent"><strong>Manage Student</strong></a>
                <a class="collapse-item" href="/promotion"><strong>Manage Promotion</strong></a>
                <a class="collapse-item" href="#"><strong>Attendance</strong></a>
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#calender"
            aria-expanded="true" aria-controls="calender">
            <i class="icofont-ui-calendar"></i>
           <strong>Calendar</strong>
        </a>
        <div id="calender" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="/calender"><strong>Manage Calendar</strong></a>
                <!-- <a class="collapse-item" href="utilities-border.html">Manage Term</a> -->
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#exam"
            aria-expanded="true" aria-controls="exam">
            <i class="icofont-certificate-alt-1"></i>
           <strong>Examination</strong>
        </a>
        <div id="exam" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                 <a href="/subjectallocation" class="collapse-item"><strong>Subject Allocation</strong></a>
                <a class="collapse-item" href="/examination"><strong>Add CA/Exam</strong></a>
                <!-- <a class="collapse-item" href="utilities-border.html"><strong>Manage Result</strong></a>
                <a class="collapse-item" href="utilities-border.html">View Result</a> -->
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#promotion"
            aria-expanded="true" aria-controls="promotion">
            <i class="icofont-exchange"></i>
           <strong>Promotion</strong>
        </a>
        <div id="promotion" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="#">Manage Promotion</a>
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#finance"
            aria-expanded="true" aria-controls="finance">
            <i class="fas fa-money-bill"></i>
           <strong>Finance</strong>
        </a>
        <div id="finance" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="#"><strong>Manage School Fees</strong></a>
            </div>
        </div>
    </li>

    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reportsheet"
            aria-expanded="true" aria-controls="reportsheet">
            <i class="icofont-pie-chart"></i>
           <strong>Report Sheet</strong>
        </a>
        <div id="reportsheet" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="/report"><strong>Generate Report</strong></a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider my-0">
    <li class="nav-item active">
        <a class="nav-link" href="/logout">
        <i class="fas fa-sign-out-alt"></i>
        Logout</a>
    </li>


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

            <!-- Sidebar Message -->
</ul>
