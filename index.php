<?php
session_start();
include('config/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IskolarEase</title>
  <link rel="icon" type="image/png" href="https://ucc.bsit4c.com/isko/uploads/logo.png">
  <link rel="shortcut icon" href="https://ucc.bsit4c.com/isko/uploads/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --main-orange: #FA983B;
      --accent-green: #40B54A;
      --dark-color: #343a40;
      --light-bg: #f8f9fa;
      --text-dark: #2d3748;
      --text-light: #718096;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: var(--text-dark);
      margin-top: 0;
      overflow-x: hidden;
    }

    .navbar {
      background: linear-gradient(135deg, var(--main-orange) 0%, #e87e2c 100%) !important;
      padding: 15px 0;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .navbar.scrolled {
      padding: 10px 0;
      background: rgba(250, 152, 59, 0.95) !important;
    }

    .navbar-brand {
      color: white !important;
      font-weight: 700;
      font-size: 1.6rem;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: all 0.3s ease;
    }

    .navbar-brand:hover {
      transform: translateY(-2px);
    }

    .navbar-brand img {
      transition: transform 0.3s ease;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    .navbar-brand:hover img {
      transform: scale(1.1);
    }

    .navbar-toggler {
      border: none;
      padding: 4px 8px;
    }

    .navbar-toggler:focus {
      box-shadow: none;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='white' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .navbar-nav .nav-link {
      color: white !important;
      font-weight: 600;
      margin: 0 8px;
      padding: 10px 20px !important;
      border-radius: 8px;
      transition: all 0.3s ease;
      position: relative;
      font-size: 1rem;
    }

    .navbar-nav .nav-link::before {
      content: '';
      position: absolute;
      bottom: 5px;
      left: 20px;
      width: 0;
      height: 2px;
      background: white;
      transition: width 0.3s ease;
    }

    .navbar-nav .nav-link:hover::before {
      width: calc(100% - 40px);
    }

    .navbar-nav .nav-link:hover {
      background-color: rgba(255,255,255,0.15);
      transform: translateY(-2px);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--accent-green) 0%, #369a3f 100%);
      color: white !important;
      font-weight: 600;
      border-radius: 10px;
      padding: 10px 24px;
      margin-left: 15px;
      border: none;
      box-shadow: 0 4px 15px rgba(64, 181, 74, 0.3);
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(64, 181, 74, 0.4);
      background: linear-gradient(135deg, #369a3f 0%, var(--accent-green) 100%);
      color: white !important;
    }

    .hero {
      background: linear-gradient(135deg, rgba(250, 152, 59, 0.85) 0%, rgba(64, 181, 74, 0.75) 100%),
                  url('uploads/ucc.jpg') no-repeat center center;
      background-size: cover;
      background-attachment: fixed;
      color: white;
      padding: 180px 0 120px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,0 1000,50 1000,100 0,100"/></svg>') bottom center/cover no-repeat;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-weight: 800;
      font-size: clamp(2.8rem, 6vw, 4.5rem);
      margin-bottom: 1.5rem;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
      background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1.2;
    }

    .hero p {
      font-size: clamp(1.2rem, 2.5vw, 1.8rem);
      margin-bottom: 2.5rem;
      font-weight: 300;
      opacity: 0.95;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
    }

    .hero-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 2rem;
    }

    .btn-main {
      background: linear-gradient(135deg, var(--main-orange) 0%, #e87e2c 100%);
      color: white;
      font-size: 1.2rem;
      padding: 16px 40px;
      border: none;
      border-radius: 50px;
      font-weight: 600;
      box-shadow: 0 8px 25px rgba(250, 152, 59, 0.4);
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-main:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(250, 152, 59, 0.5);
      background: linear-gradient(135deg, #e87e2c 0%, var(--main-orange) 100%);
      color: white;
    }

    .btn-secondary {
      background: transparent;
      border: 3px solid white;
      color: white;
      font-size: 1.2rem;
      padding: 16px 40px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      backdrop-filter: blur(10px);
    }

    .btn-secondary:hover {
      background: white;
      color: var(--dark-color);
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(255,255,255,0.3);
    }

    .features {
      padding: 100px 0;
      background: var(--light-bg);
      position: relative;
    }

    .features::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(135deg, var(--main-orange) 0%, var(--accent-green) 100%);
    }

    .features h2, .about h2, .scholarships h2, #howtoapply h2 {
      color: var(--accent-green);
      font-weight: 700;
      margin-bottom: 3rem;
      font-size: 2.8rem;
      text-align: center;
      position: relative;
    }

    .features h2::after, .about h2::after, .scholarships h2::after, #howtoapply h2::after {
      content: '';
      position: absolute;
      bottom: -15px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 5px;
      background: linear-gradient(135deg, var(--main-orange) 0%, var(--accent-green) 100%);
      border-radius: 3px;
    }

    .feature-box {
      text-align: center;
      padding: 50px 30px;
      border-radius: 20px;
      transition: all 0.4s ease;
      background: white;
      border: 1px solid #e9ecef;
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .feature-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(135deg, var(--main-orange) 0%, var(--accent-green) 100%);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .feature-box:hover::before {
      transform: scaleX(1);
    }

    .feature-box:hover {
      transform: translateY(-12px);
      box-shadow: 0 20px 50px rgba(0,0,0,0.15);
    }

    .feature-icon {
      font-size: 3.5rem;
      margin-bottom: 25px;
      display: block;
      transition: transform 0.3s ease;
    }

    .feature-box:hover .feature-icon {
      transform: scale(1.1);
    }

    .feature-box h4 {
      font-size: 1.5rem;
      margin: 20px 0 20px;
      color: var(--dark-color);
      font-weight: 700;
    }

    .feature-box p {
      color: var(--text-light);
      font-size: 1.1rem;
      line-height: 1.7;
      margin: 0;
    }

    .about {
      background: linear-gradient(135deg, #fff 0%, var(--light-bg) 100%);
      padding: 100px 0;
      position: relative;
    }

    .about .content h2 {
      text-align: left;
    }

    .about .content h2::after {
      left: 0;
      transform: none;
    }

    .about p {
      font-size: 1.15rem;
      color: var(--text-light);
      line-height: 1.8;
      margin-bottom: 2rem;
    }

    .about strong {
      color: var(--main-orange);
      font-weight: 600;
    }

    .about img {
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0,0,0,0.15);
      transition: all 0.4s ease;
      filter: brightness(1.05);
    }

    .about img:hover {
      transform: scale(1.02) rotate(1deg);
      box-shadow: 0 35px 70px rgba(0,0,0,0.2);
    }

    .scholarships {
      padding: 100px 0;
      background: var(--light-bg);
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 12px 35px rgba(0,0,0,0.1);
      transition: all 0.4s ease;
      margin-bottom: 30px;
      height: 100%;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 25px 60px rgba(0,0,0,0.15);
    }

    .card-body {
      padding: 2.5rem;
    }

    .card-title {
      color: var(--main-orange);
      font-weight: 700;
      font-size: 1.4rem;
      margin-bottom: 1.2rem;
      line-height: 1.3;
    }

    .card-text {
      color: var(--text-light);
      line-height: 1.7;
      margin-bottom: 2rem;
      font-size: 1.05rem;
    }

    .btn-apply {
      background: linear-gradient(135deg, var(--accent-green) 0%, #369a3f 100%);
      color: white;
      font-weight: 600;
      border-radius: 10px;
      padding: 12px 30px;
      border: none;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      font-size: 1.05rem;
      box-shadow: 0 4px 15px rgba(64, 181, 74, 0.3);
    }

    .btn-apply:hover {
      background: linear-gradient(135deg, var(--main-orange) 0%, #e87e2c 100%);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(250, 152, 59, 0.4);
      color: white;
    }

    #howtoapply {
      padding: 100px 0;
      background: white;
      position: relative;
    }

    .step-box {
      text-align: center;
      padding: 40px 25px;
      position: relative;
      transition: all 0.3s ease;
    }

    .step-box:hover {
      transform: translateY(-5px);
    }

    .step-number {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--main-orange) 0%, var(--accent-green) 100%);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      font-weight: 700;
      margin: 0 auto 25px;
      box-shadow: 0 8px 25px rgba(250, 152, 59, 0.4);
      transition: all 0.3s ease;
    }

    .step-box:hover .step-number {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 12px 30px rgba(250, 152, 59, 0.5);
    }

    .step-box h4 {
      font-size: 1.4rem;
      margin: 20px 0 15px;
      color: var(--dark-color);
      font-weight: 700;
    }

    .step-box p {
      color: var(--text-light);
      font-size: 1.05rem;
      line-height: 1.6;
      margin: 0;
    }

    footer {
      background: linear-gradient(135deg, var(--dark-color) 0%, #2c3034 100%);
      color: white;
      text-align: center;
      padding: 40px 0 30px;
      margin-top: 60px;
      position: relative;
    }

    footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(135deg, var(--main-orange) 0%, var(--accent-green) 100%);
    }

    footer span {
      color: var(--main-orange);
      font-weight: 700;
    }

    footer p {
      margin: 0;
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .pagination {
      justify-content: center;
      margin-top: 3rem;
    }

    .pagination .page-link {
      color: var(--main-orange);
      font-weight: 600;
      border: 2px solid transparent;
      border-radius: 10px;
      padding: 10px 18px;
      margin: 0 5px;
      transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
      background: var(--main-orange);
      color: white;
      border-color: var(--main-orange);
      transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
      background: linear-gradient(135deg, var(--accent-green) 0%, #369a3f 100%);
      border-color: var(--accent-green);
      color: white;
      box-shadow: 0 4px 15px rgba(64, 181, 74, 0.3);
    }

    .loading {
      display: inline-block;
      width: 50px;
      height: 50px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid var(--main-orange);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 20px auto;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: linear-gradient(135deg, var(--main-orange) 0%, var(--accent-green) 100%);
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      box-shadow: 0 6px 20px rgba(250, 152, 59, 0.4);
      transition: all 0.3s ease;
      opacity: 0;
      visibility: hidden;
      z-index: 1000;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .back-to-top.show {
      opacity: 1;
      visibility: visible;
    }

    .back-to-top:hover {
      transform: translateY(-5px) scale(1.1);
      box-shadow: 0 10px 30px rgba(250, 152, 59, 0.6);
    }

    html {
      scroll-behavior: smooth;
    }

    @media (max-width: 768px) {
      .hero {
        padding: 140px 0 80px;
        background-attachment: scroll;
      }

      .hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 15px;
      }

      .btn-main, .btn-secondary {
        width: 100%;
        max-width: 280px;
        margin: 0;
      }

      .navbar-nav {
        text-align: center;
        padding: 15px 0;
      }

      .navbar-nav .nav-link {
        margin: 5px 0;
      }

      .btn-login {
        margin: 10px 0 0 0;
        width: 100%;
        text-align: center;
      }

      .feature-box, .step-box {
        margin-bottom: 25px;
      }

      .features h2, .about h2, .scholarships h2, #howtoapply h2 {
        font-size: 2.2rem;
      }

      .back-to-top {
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
      }
    }

    .fade-in {
      animation: fadeInUp 0.8s ease-out forwards;
      opacity: 0;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-in {
      animation: fadeInUp 0.8s ease-out forwards;
    }

    section {
      position: relative;
    }
  </style>
</head>
<body>

<a href="#" class="back-to-top">↑</a>

<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="uploads/logo.png" alt="IskolarEase Logo" height="55" class="me-2">
      <span class="fw-bold">IskolarEase</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#scholarships">Scholarships</a></li>
        <li class="nav-item"><a class="nav-link" href="#howtoapply">How to Apply</a></li>
        <li class="nav-item"><a class="nav-link btn-login" href="login.php">Sign In</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="hero">
  <div class="container">
    <div class="hero-content">
      <h1>Welcome to IskolarEase</h1>
      <p>Your Gateway to Scholarships at the University of Caloocan City</p>
      <div class="hero-buttons">
        <a href="register.php" class="btn-main">Apply Now</a>
        <a href="#scholarships" class="btn-secondary">View Scholarships</a>
      </div>
    </div>
  </div>
</section>

<section class="py-5 features">
  <div class="container text-center">
    <h2>Why Use IskolarEase?</h2>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="feature-box fade-in">
          <span class="feature-icon">📝</span>
          <h4>Digital Application</h4>
          <p>Apply online without filling up paper forms. Quick and eco-friendly process.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-box fade-in">
          <span class="feature-icon">✅</span>
          <h4>Smart Eligibility</h4>
          <p>Instantly know if you qualify for scholarships with our intelligent checker.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-box fade-in">
          <span class="feature-icon">📊</span>
          <h4>Real-time Tracking</h4>
          <p>Monitor your application status in real-time with detailed progress updates.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="feature-box fade-in">
          <span class="feature-icon">🔔</span>
          <h4>Smart Notifications</h4>
          <p>Get instant updates on approvals, requirements, or important deadlines.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="about" class="about">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <img src="uploads\isko.png" alt="About IskolarEase" class="img-fluid rounded shadow-sm fade-in">
      </div>
      <div class="col-md-6 content">
        <h2>About IskolarEase</h2>
        <p>
          IskolarEase is a scholarship management platform developed for the
          <strong>University of Caloocan City</strong>. It was built to simplify the scholarship
          application process for students by providing a fully digital, easy-to-use,
          and accessible system.
        </p>
        <p>
          Through IskolarEase, students can browse available scholarships, check their
          eligibility, apply online, and track their application status in real-time.
          The platform also ensures transparency by sending instant updates and
          notifications to keep students informed about their progress.
        </p>
        <p>
          Our mission is to <strong>empower students</strong> by making scholarship opportunities
          more accessible and ensuring that no qualified scholar is left behind.
        </p>
      </div>
    </div>
  </div>
</section>

<section id="scholarships" class="py-5 bg-light scholarships">
  <div class="container">
    <h2 class="text-center">Available Scholarships</h2>
    <div id="scholarshipContainer" class="row justify-content-center">
      <div class="col-12 text-center">
        <div class="loading"></div>
        <p class="text-muted mt-2">Loading scholarships...</p>
      </div>
    </div>
    <div id="paginationContainer" class="mt-3"></div>
  </div>
</section>

<section id="howtoapply" class="py-5">
  <div class="container text-center">
    <h2>How It Works</h2>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="step-box fade-in">
          <div class="step-number">1</div>
          <h4>Create Account</h4>
          <p>Sign up with your student credentials and verify your email</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="step-box fade-in">
          <div class="step-number">2</div>
          <h4>Browse Scholarships</h4>
          <p>Discover available opportunities that match your qualifications</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="step-box fade-in">
          <div class="step-number">3</div>
          <h4>Submit Application</h4>
          <p>Upload required documents and submit your application online</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="step-box fade-in">
          <div class="step-number">4</div>
          <h4>Track Progress</h4>
          <p>Monitor your application status with real-time updates</p>
        </div>
      </div>
    </div>
  </div>
</section>

<footer>
  <p>&copy; <?php echo date("Y"); ?> University of Caloocan City – <span>IskolarEase</span> | Developed by BSIT Students</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', function() {
    if (window.scrollY > 100) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  const backToTop = document.querySelector('.back-to-top');
  window.addEventListener('scroll', function() {
    if (window.scrollY > 300) {
      backToTop.classList.add('show');
    } else {
      backToTop.classList.remove('show');
    }
  });

  backToTop.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

  loadScholarships(1);

  function loadScholarships(page) {
    const container = document.getElementById("scholarshipContainer");

    fetch("fetch_scholarships.php?page=" + page)
      .then(response => response.text())
      .then(data => {
        const parts = data.split("<!--SPLIT-->");
        document.getElementById("scholarshipContainer").innerHTML = parts[0];
        document.getElementById("paginationContainer").innerHTML = parts[1];

        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
          card.style.animationDelay = `${index * 0.1}s`;
          card.classList.add('fade-in');
        });

        document.querySelectorAll(".pagination a").forEach(link => {
          link.addEventListener("click", function (e) {
            e.preventDefault();
            const page = this.getAttribute("data-page");
            loadScholarships(page);
            document.getElementById('scholarships').scrollIntoView({
              behavior: 'smooth'
            });
          });
        });
      })
      .catch(error => {
        console.error('Error loading scholarships:', error);
        container.innerHTML = `
          <div class="col-12 text-center">
            <div class="alert alert-danger" role="alert">
              <h5>Failed to load scholarships</h5>
              <p class="mb-0">Please check your internet connection and try again.</p>
            </div>
          </div>
        `;
      });
  }

  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-in');
      }
    });
  }, observerOptions);

  document.querySelectorAll('.feature-box, .step-box, .card').forEach(el => {
    observer.observe(el);
  });
});
</script>

</body>
</html>
