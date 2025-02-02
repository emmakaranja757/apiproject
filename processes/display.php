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
      width: 100%;  /* Ensures the image spans the full width of the carousel */
      max-height: 520px;  /* Set maximum height to 400px, adjust as needed */
      object-fit: cover;  /* Ensures images are cropped to fit the container without stretching */
      margin: 0 auto;  /* Centers the image */
    }

    /* Navbar Customization */
    .navbar {
      background-color: #343a40; /* Charcoal Grey */
    }
    .navbar-nav .nav-link {
      font-size: 1.2rem;  /* Increase font size */
    }
    .navbar-brand img {
      width: 50px;  /* Adjust logo size */
      height: 50px;
    }
    .nav-link:hover {
      background-color: #343a40;  /* Add hover effect on links */
    }
    .navbar-toggler-icon {
      background-color: white;  /* Change the color of the hamburger menu */
    }
  </style>
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://via.placeholder.com/50" alt="Logo" class="d-inline-block align-text-top">
        <span class="ms-2">RealEstateCo</span> <!-- Added space between logo and text -->
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#features">Features</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#pricing">Pricing</a>
          </li>
          <!-- Dropdown Menu -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Services
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Buy</a></li>
              <li><a class="dropdown-item" href="#">Rent</a></li>
              <li><a class="dropdown-item" href="#">Invest</a></li>
            </ul>
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
      <p>Welcome to RealEstateCo, your trusted partner in finding the perfect property. Whether you're looking to buy, rent, or invest, we have the right options for you!</p>
 <!-- About Us Dropdown -->
    <div class="dropdown mt-4">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="aboutDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          Learn More
        </button>
        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
          <li><a class="dropdown-item" href="#">Our History</a></li>
          <li><a class="dropdown-item" href="#">Our Team</a></li>
          <li><a class="dropdown-item" href="#">Our Values</a></li>
        </ul>
      </div>
    </section>
    
    <!-- About Us Detailed Sections -->
    <section id="ourHistory" class="mt-5">
      <h3>Our History</h3>
      <p>RealEstateCo was founded in 2010 with the vision of providing affordable housing options to people across the city. Over the years, we've grown into one of the leading real estate platforms, offering both rental and investment opportunities.</p>
    </section>

    <section id="ourTeam" class="mt-5">
      <h3>Our Team</h3>
      <p>Our team consists of experienced real estate professionals who are dedicated to helping you find the perfect property. From agents to support staff, everyone at RealEstateCo is committed to ensuring a smooth experience for you.</p>
    </section>

    <section id="ourValues" class="mt-5">
      <h3>Our Values</h3>
      <p>At RealEstateCo, we value transparency, honesty, and customer satisfaction. We aim to provide our clients with the best possible options, ensuring that every decision is made with integrity.</p>
    </section>EstateCo, we value transparency, honesty, and customer satisfaction. We aim to provide our clients with the best possible options, ensuring that every decision is made with integrity.</p>
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
  <footer class="bg-primary text-white text-center py-3 mt-5">
    <p>&copy; 2025 RealEstateCo. All Rights Reserved.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
