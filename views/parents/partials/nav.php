<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="/home" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><img src="img/bbtma.png" alt="" style="width: 50px;"></i>Baraya Baban Takko Memorial Academy</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="/home" class="nav-item nav-link active">Home</a>
                <a href="/home#about" class="nav-item nav-link">About</a>
                <!-- <a href="#" class="nav-item nav-link">Classes</a> -->
                <!-- <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                    <div class="dropdown-menu fade-down m-0">
                        <a href="team.html" class="dropdown-item">Our Team</a>
                        <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                        <a href="404.html" class="dropdown-item">404 Page</a>
                    </div>
                </div> -->
                <a href="/home#contact" class="nav-item nav-link">Contact</a>
                <a href="" class="nav-item nav-link" data-bs-toggle="modal" data-bs-target="#myModal">Login</a>
            </div>
            <a href="#" data-bs-toggle="modal" data-bs-target="#applicant" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Apply Now<!-- <i class="fa fa-arrow-right ms-3"></i> --></a>
        </div>
    </nav>
    <!-- Navbar End -->

<!-- Modal -->
<div class="modal fade" id="applicant" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Applicant Registration</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="applicant">
            <div class="form-group">
                <label for="studentEmail">Serial no.</label>
                <input id="studentEmail" class="form-control" type="text" name="studentUsername">
                <small class="text-danger" id="email"></small>
            </div>
            <div class="form-group">
                <label for="studentPassword">PIN Code</label>
                <input id="studentPassword" class="form-control" type="password" name="studentPassword">
                <small class="text-danger" id="password"></small>
            </div>
            <button class="btn btn-primary mt-3">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Parent Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="parentLogin">

            <div class="form-group">
                <label for="studentEmail">Student Email/Phone</label>
                <input id="studentEmail" class="form-control" type="text" name="username">
                <small class="text-danger" id="username_error"></small> 
            </div>

            <div class="form-group">
                <label for="studentEmail">Password</label>
                <input id="password" class="form-control" type="password" name="password">
                <small class="text-danger" id="password_error"></small> 
            </div>
            <button type="submit" class="btn btn-primary mt-3">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
    $('#parentLogin').on('submit', function(e){
        e.preventDefault();
        $('.text-danger').text('');

        $.ajax({
            url: 'model/parents/login.php',
            dataType: 'JSON',
            data: $(this).serialize(),
            type: 'POST',
            success: function(response){
                console.log(response);
                if(response.status){
                    Swal.fire({
                        icon: "success",
                        title: response.success.message,
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $('#parentLogin')[0].reset();
                    setTimeout(function(){
                        window.location.href = response.success.redirect
                    }, 1000)
                } else {
                    if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            console.log(key, value); 
                            $('#' + key).text(value);
                        });
                    } else {
                        alert("Login failed");
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("An error occurred during the request.");
            }
        });
    });
});


</script>

