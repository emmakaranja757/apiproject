<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Real Estate Platform</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS for Navbar and Slideshow -->
  <style>
    /* Slideshow CSS */
    .carousel-inner img {
      width: 100%;
      max-height: 520px;
      object-fit: cover;
      margin: 0 auto;
    }

    /* Navbar Customization */
    .navbar {
      background-color: #343a40;
    }
    .navbar-nav .nav-link {
      font-size: 1.2rem;
    }
    .navbar-brand img {
      width: 50px;
      height: 50px;
    }
    .nav-link:hover {
      background-color: #343a40;
    }
    .navbar-toggler-icon {
      background-color: white;
    }
  </style>
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://via.placeholder.com/50" alt="Logo" class="d-inline-block align-text-top">
        <span class="ms-2">RealEstateCo</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              About Us
            </a>
            <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
              <li><a class="dropdown-item" href="#about">Overview</a></li>
              <li><a class="dropdown-item" href="#ourHistory">Our History</a></li>
              <li><a class="dropdown-item" href="#ourTeam">Our Team</a></li>
              <li><a class="dropdown-item" href="#ourValues">Our Values</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#features">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#pricing">Pricing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="signup.php">Sign Up</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">Log In</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Slideshow -->
  <div id="propertyCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="../IMAGES/image1.jpg" class="d-block w-100" alt="Property 1">
        <div class="carousel-caption d-none d-md-block">
          <h5>247 Vitra Road Three</h5>
          <p>Rent: $3,000</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../IMAGES/image2.jpg" class="d-block w-100" alt="Property 2">
        <div class="carousel-caption d-none d-md-block">
          <h5>Luxury Villa</h5>
          <p>Rent: $5,000</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="../IMAGES/image3.jpg" class="d-block w-100" alt="Property 3">
        <div class="carousel-caption d-none d-md-block">
          <h5>Modern Apartment</h5>
          <p>Rent: $2,500</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- Content Section -->
  <div class="container mt-5">
    <section id="about">
      <h2>About Us</h2>
      <p>Welcome to RealEstateCo, your trusted partner in finding the perfect property.</p>
    </section>

    <section id="ourHistory" class="mt-5">
      <h3>Our History</h3>
      <p>RealEstateCo was founded in 2010 with the vision of providing affordable housing options.</p>
    </section>

    <section id="ourTeam" class="mt-5">
      <h3>Our Team</h3>
      <p>Our team consists of experienced real estate professionals dedicated to helping you find the perfect property.</p>
    </section>

    <section id="features" class="mt-5">
      <h2>Features</h2>
      <ul>
        <li>Browse a wide range of properties</li>
        <li>Easy-to-use filters for finding the perfect match</li>
        <li>Secure online transactions</li>
        <li>Personalized recommendations</li>
      </ul>
    </section>

    <section id="pricing" class="mt-5">
      <h2>Pricing</h2>
      <p>Contact us for detailed pricing information and exclusive offers!</p>
    </section>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white pt-4">
  <div class="container">
    <div class="row">
      <!-- Company Info -->
      <div class="col-md-4 mb-3">
        <h5>Sawabu RealEstate</h5>
        <p>Providing top-notch real estate solutions for buyers, renters, and investors.</p>
      </div>
      
      <!-- Contact Info -->
      <div class="col-md-4 mb-3">
        <h5>Contact Us</h5>
        <ul class="list-unstyled">
          <li><strong>Email:</strong> info@sawaburealestateco.com</li>
          <li><strong>Phone:</strong> +254700976434</li>
          <li><strong>Address:</strong> Kiambu rd, Kiambu, Kenya</li>
        </ul>
      </div>
      
      <!-- Newsletter Subscription -->
      <div class="col-md-4 mb-3">
        <h5>Subscribe to Our Newsletter</h5>
        <form>
          <div class="mb-2">
            <input type="email" class="form-control" placeholder="Enter your email" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Subscribe</button>
        </form>
      </div>
    </div>
    <hr>
    <div class="text-center">
      <p>Copyright &copy; 2025 Sawabu RealEstate | All Rights Reserved | <a href="#" class="text-white">Privacy Policy</a> | <a href="#" class="text-white">Check Mail</a> | Powered By Heartbit</p>
    </div>
  </div>
</footer>



<!-- Font Awesome for icons -->
<script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>

  
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
