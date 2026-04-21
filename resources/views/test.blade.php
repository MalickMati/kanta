<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Faheem Photography & Films | Visual Storytelling</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #0a0a0a;
            --secondary-dark: #1a1a1a;
            --accent: #c19a6b;
            --accent-light: #e8d0b3;
            --text-dark: #f5f5f5;
            --text-gray-dark: #aaaaaa;
            
            --primary-light: #f9f7f3;
            --secondary-light: #ffffff;
            --accent-light-mode: #8b7355;
            --text-light: #333333;
            --text-gray-light: #666666;
            
            --transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --section-padding: 80px 0;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --card-shadow-dark: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-dark);
            color: var(--text-dark);
            overflow-x: hidden;
            line-height: 1.6;
            transition: var(--transition);
            font-size: 16px;
        }
        
        body.light-mode {
            background-color: var(--primary-light);
            color: var(--text-light);
        }
        
        h1, h2, h3, h4 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Mobile First Adjustments */
        @media (max-width: 768px) {
            :root {
                --section-padding: 60px 0;
            }
            
            body {
                font-size: 15px;
            }
        }
        
        @media (max-width: 480px) {
            :root {
                --section-padding: 50px 0;
            }
            
            body {
                font-size: 14px;
            }
        }
        
        /* Theme Toggle - Mobile Optimized */
        .theme-toggle {
            position: fixed;
            top: 10px;
            right: 75px;
            z-index: 1001;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: rgba(10, 10, 10, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        @media (min-width: 768px) {
            .theme-toggle {
                top: 30px;
                right: 30px;
                width: 60px;
                height: 60px;
            }
        }
        
        body.light-mode .theme-toggle {
            background-color: rgba(249, 247, 243, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .theme-toggle i {
            font-size: 20px;
            color: var(--accent);
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            .theme-toggle i {
                font-size: 24px;
            }
        }
        
        body.light-mode .theme-toggle i {
            color: var(--accent-light-mode);
        }
        
        .theme-toggle:hover, .theme-toggle:active {
            transform: rotate(30deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Header & Navigation - Mobile Optimized */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 15px 0;
            background-color: transparent;
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            header {
                padding: 25px 0;
            }
        }
        
        header.scrolled {
            padding: 10px 0;
            background-color: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        
        @media (min-width: 768px) {
            header.scrolled {
                padding: 15px 0;
            }
        }
        
        body.light-mode header.scrolled {
            background-color: rgba(249, 247, 243, 0.95);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        
        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--accent);
            text-decoration: none;
            letter-spacing: 1px;
            position: relative;
            display: flex;
            align-items: center;
            z-index: 1002;
        }
        
        @media (min-width: 768px) {
            .logo {
                font-size: 32px;
            }
        }
        
        body.light-mode .logo {
            color: var(--accent-light-mode);
        }
        
        .logo span {
            color: var(--text-dark);
            margin-left: 4px;
            display: inline-block;
        }
        
        @media (min-width: 768px) {
            .logo span {
                margin-left: 5px;
            }
        }
        
        body.light-mode .logo span {
            color: var(--text-light);
        }
        
        .logo:before {
            content: '';
            position: absolute;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: rgba(193, 154, 107, 0.1);
            z-index: -1;
            left: -5px;
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            .logo:before {
                width: 40px;
                height: 40px;
            }
        }
        
        .logo:hover:before, .logo:active:before {
            transform: scale(1.2);
        }
        
        .logo i {
            font-size: 16px;
            margin-right: 8px;
            color: var(--accent);
        }
        
        @media (min-width: 768px) {
            .logo i {
                font-size: 20px;
                margin-right: 10px;
            }
        }
        
        /* Mobile Menu */
        .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 22px;
            cursor: pointer;
            transition: var(--transition);
            width: 40px;
            height: 40px;
            z-index: 1002;
            border-radius: 4px;
        }
        
        body.light-mode .mobile-menu-btn {
            color: var(--text-light);
        }
        
        .mobile-menu-btn:hover, .mobile-menu-btn:active {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        body.light-mode .mobile-menu-btn:hover,
        body.light-mode .mobile-menu-btn:active {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        @media (min-width: 992px) {
            .mobile-menu-btn {
                display: none;
            }

            .theme-toggle {
                right: 20px;
            }
        }
        
        .nav-links {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 400px;
            height: 100vh;
            background-color: var(--secondary-dark);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            padding: 80px 40px 40px;
            list-style: none;
            transition: var(--transition);
            z-index: 1001;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.3);
        }
        
        body.light-mode .nav-links {
            background-color: var(--secondary-light);
        }
        
        .nav-links.active {
            right: 0;
        }
        
        .nav-links li {
            margin: 0 0 30px 0;
            width: 100%;
        }
        
        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            transition: var(--transition);
            position: relative;
            padding: 8px 0;
            display: block;
            width: 100%;
        }
        
        body.light-mode .nav-links a {
            color: var(--text-light);
        }
        
        .nav-links a:hover, .nav-links a:active {
            color: var(--accent);
        }
        
        body.light-mode .nav-links a:hover,
        body.light-mode .nav-links a:active {
            color: var(--accent-light-mode);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent);
            transition: var(--transition);
        }
        
        body.light-mode .nav-links a::after {
            background-color: var(--accent-light-mode);
        }
        
        .nav-links a:hover::after, .nav-links a:active::after {
            width: 100%;
        }
        
        /* Desktop Navigation */
        @media (min-width: 992px) {
            .nav-links {
                position: static;
                width: auto;
                height: auto;
                background-color: transparent;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
                padding: 0;
                box-shadow: none;
                right: 0;
            }
            
            .nav-links li {
                margin: 0 0 0 40px;
                width: auto;
            }
            
            .nav-links a {
                font-size: 16px;
                padding: 5px 0;
            }
            
            body.light-mode .nav-links {
                background-color: transparent;
            }
        }
        
        /* Close Menu Overlay */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }
        
        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Hero Section - Mobile Optimized */
        .hero {
            height: 100vh;
            min-height: 600px;
            max-height: 1200px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }
        
        @media (min-width: 768px) {
            .hero {
                padding-top: 0;
                min-height: 700px;
            }
        }
        
        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9)), url("{{ asset('photo.jpg') }}") center/cover no-repeat;
            z-index: -1;
            transition: var(--transition);
        }
        
        @media (max-width: 768px) {
            .hero-bg {
                background-position: 75% center;
            }
        }
        
        body.light-mode .hero-bg {
            background: linear-gradient(rgba(249, 247, 243, 0.85), rgba(249, 247, 243, 0.95)), url("{{ asset('photo.jpg') }}") center/cover no-repeat;
        }
        
        .hero-content {
            max-width: 100%;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1.2s forwards 0.5s;
        }
        
        @media (min-width: 768px) {
            .hero-content {
                max-width: 900px;
            }
        }
        
        .hero h1 {
            font-size: 36px;
            margin-bottom: 20px;
            line-height: 1.2;
            font-weight: 400;
        }
        
        @media (min-width: 576px) {
            .hero h1 {
                font-size: 42px;
            }
        }
        
        @media (min-width: 768px) {
            .hero h1 {
                font-size: 56px;
                margin-bottom: 25px;
            }
        }
        
        @media (min-width: 992px) {
            .hero h1 {
                font-size: 72px;
            }
        }
        
        body.light-mode .hero h1 {
            color: var(--text-light);
        }
        
        .hero h1 span {
            color: var(--accent);
            font-weight: 600;
            position: relative;
            display: inline-block;
        }
        
        body.light-mode .hero h1 span {
            color: var(--accent-light-mode);
        }
        
        .hero h1 span:after {
            content: '';
            position: absolute;
            bottom: 3px;
            left: 0;
            width: 100%;
            height: 6px;
            background-color: rgba(193, 154, 107, 0.2);
            z-index: -1;
        }
        
        @media (min-width: 768px) {
            .hero h1 span:after {
                bottom: 5px;
                height: 8px;
            }
        }
        
        .hero p {
            font-size: 16px;
            color: var(--text-gray-dark);
            margin-bottom: 40px;
            max-width: 100%;
        }
        
        @media (min-width: 576px) {
            .hero p {
                font-size: 18px;
            }
        }
        
        @media (min-width: 768px) {
            .hero p {
                font-size: 20px;
                margin-bottom: 50px;
                max-width: 700px;
            }
        }
        
        body.light-mode .hero p {
            color: var(--text-gray-light);
        }
        
        .btn {
            display: inline-block;
            padding: 14px 30px;
            background-color: var(--accent);
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 600;
            border-radius: 0;
            transition: var(--transition);
            border: 2px solid var(--accent);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 13px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-align: center;
            width: auto;
            -webkit-tap-highlight-color: transparent;
        }
        
        @media (min-width: 768px) {
            .btn {
                padding: 18px 40px;
                font-size: 14px;
                letter-spacing: 2px;
            }
        }
        
        body.light-mode .btn {
            background-color: var(--accent-light-mode);
            color: var(--primary-light);
            border-color: var(--accent-light-mode);
        }
        
        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background-color: transparent;
            transition: var(--transition);
            z-index: -1;
        }
        
        .btn:hover, .btn:active {
            color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(193, 154, 107, 0.3);
        }
        
        @media (min-width: 768px) {
            .btn:hover, .btn:active {
                transform: translateY(-5px);
            }
        }
        
        body.light-mode .btn:hover,
        body.light-mode .btn:active {
            color: var(--accent-light-mode);
        }
        
        .btn:hover:before, .btn:active:before {
            left: 0;
            background-color: var(--primary-dark);
        }
        
        body.light-mode .btn:hover:before,
        body.light-mode .btn:active:before {
            background-color: var(--primary-light);
        }
        
        /* Portfolio Section - Mobile Optimized */
        .portfolio {
            padding: var(--section-padding);
            background-color: var(--secondary-dark);
            transition: var(--transition);
        }
        
        body.light-mode .portfolio {
            background-color: var(--secondary-light);
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            opacity: 0;
            transform: translateY(30px);
        }
        
        @media (min-width: 768px) {
            .section-title {
                margin-bottom: 80px;
            }
        }
        
        .section-title.in-view {
            animation: fadeUp 0.8s forwards;
        }
        
        .section-title h2 {
            font-size: 36px;
            margin-bottom: 15px;
            color: var(--text-dark);
        }
        
        @media (min-width: 576px) {
            .section-title h2 {
                font-size: 42px;
            }
        }
        
        @media (min-width: 768px) {
            .section-title h2 {
                font-size: 48px;
                margin-bottom: 20px;
            }
        }
        
        @media (min-width: 992px) {
            .section-title h2 {
                font-size: 56px;
            }
        }
        
        body.light-mode .section-title h2 {
            color: var(--text-light);
        }
        
        .section-title p {
            color: var(--text-gray-dark);
            font-size: 16px;
            max-width: 100%;
            margin: 0 auto;
            font-weight: 300;
            padding: 0 10px;
        }
        
        @media (min-width: 576px) {
            .section-title p {
                font-size: 18px;
            }
        }
        
        @media (min-width: 768px) {
            .section-title p {
                font-size: 20px;
                max-width: 700px;
                padding: 0;
            }
        }
        
        body.light-mode .section-title p {
            color: var(--text-gray-light);
        }
        
        /* Portfolio Filter - Mobile Optimized */
        .portfolio-filter {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 40px;
            opacity: 0;
            transform: translateY(30px);
            gap: 10px;
        }
        
        @media (min-width: 768px) {
            .portfolio-filter {
                margin-bottom: 60px;
                gap: 0;
            }
        }
        
        .portfolio-filter.in-view {
            animation: fadeUp 0.8s forwards 0.2s;
        }
        
        .filter-btn {
            background: none;
            border: none;
            color: var(--text-gray-dark);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            margin: 0 5px 5px;
            padding: 8px 15px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            letter-spacing: 0.5px;
            border-radius: 4px;
            -webkit-tap-highlight-color: transparent;
        }
        
        @media (min-width: 576px) {
            .filter-btn {
                font-size: 15px;
                margin: 0 8px 8px;
                padding: 8px 20px;
            }
        }
        
        @media (min-width: 768px) {
            .filter-btn {
                font-size: 16px;
                margin: 0 15px 15px;
                padding: 10px 0;
                border-radius: 0;
            }
        }
        
        body.light-mode .filter-btn {
            color: var(--text-gray-light);
        }
        
        .filter-btn.active, .filter-btn:hover, .filter-btn:active {
            color: var(--accent);
        }
        
        body.light-mode .filter-btn.active,
        body.light-mode .filter-btn:hover,
        body.light-mode .filter-btn:active {
            color: var(--accent-light-mode);
        }
        
        .filter-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: var(--accent);
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            .filter-btn::after {
                left: 0;
                transform: none;
            }
        }
        
        body.light-mode .filter-btn::after {
            background-color: var(--accent-light-mode);
        }
        
        .filter-btn.active::after, .filter-btn:hover::after, .filter-btn:active::after {
            width: 80%;
        }
        
        @media (min-width: 768px) {
            .filter-btn.active::after, 
            .filter-btn:hover::after, 
            .filter-btn:active::after {
                width: 100%;
            }
        }
        
        /* Portfolio Grid - Mobile Optimized */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }
        
        @media (min-width: 576px) {
            .portfolio-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 25px;
            }
        }
        
        @media (min-width: 768px) {
            .portfolio-grid {
                grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
                gap: 30px;
            }
        }
        
        .portfolio-item {
            position: relative;
            border-radius: 5px;
            overflow: hidden;
            height: 300px;
            opacity: 0;
            transform: translateY(30px) scale(0.95);
            transition: var(--transition);
            box-shadow: var(--card-shadow-dark);
        }
        
        @media (min-width: 576px) {
            .portfolio-item {
                height: 320px;
            }
        }
        
        @media (min-width: 768px) {
            .portfolio-item {
                height: 350px;
            }
        }
        
        body.light-mode .portfolio-item {
            box-shadow: var(--card-shadow);
        }
        
        .portfolio-item.in-view {
            animation: fadeUpScale 0.8s forwards;
        }
        
        .portfolio-item:nth-child(2) { animation-delay: 0.1s; }
        .portfolio-item:nth-child(3) { animation-delay: 0.2s; }
        .portfolio-item:nth-child(4) { animation-delay: 0.3s; }
        .portfolio-item:nth-child(5) { animation-delay: 0.4s; }
        .portfolio-item:nth-child(6) { animation-delay: 0.5s; }
        
        .portfolio-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.9));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 25px;
            opacity: 0;
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            .portfolio-overlay {
                padding: 40px;
            }
        }
        
        body.light-mode .portfolio-overlay {
            background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.7));
        }
        
        .portfolio-item:hover .portfolio-overlay,
        .portfolio-item:active .portfolio-overlay {
            opacity: 1;
        }
        
        .portfolio-item:hover .portfolio-img,
        .portfolio-item:active .portfolio-img {
            transform: scale(1.08);
        }
        
        .portfolio-overlay h3 {
            font-size: 22px;
            margin-bottom: 8px;
            transform: translateY(20px);
            transition: var(--transition);
            transition-delay: 0.1s;
        }
        
        @media (min-width: 768px) {
            .portfolio-overlay h3 {
                font-size: 28px;
                margin-bottom: 10px;
                transform: translateY(30px);
            }
        }
        
        .portfolio-overlay p {
            color: var(--text-gray-dark);
            font-size: 14px;
            transform: translateY(20px);
            transition: var(--transition);
            transition-delay: 0.2s;
        }
        
        @media (min-width: 768px) {
            .portfolio-overlay p {
                font-size: 16px;
                transform: translateY(30px);
            }
        }
        
        .portfolio-item:hover .portfolio-overlay h3,
        .portfolio-item:hover .portfolio-overlay p,
        .portfolio-item:active .portfolio-overlay h3,
        .portfolio-item:active .portfolio-overlay p {
            transform: translateY(0);
        }
        
        .video-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: var(--accent);
            color: var(--primary-dark);
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            opacity: 0;
            transform: translateX(15px);
            transition: var(--transition);
            transition-delay: 0.3s;
        }
        
        @media (min-width: 768px) {
            .video-badge {
                top: 25px;
                right: 25px;
                padding: 8px 15px;
                font-size: 12px;
                transform: translateX(20px);
            }
        }
        
        body.light-mode .video-badge {
            background-color: var(--accent-light-mode);
            color: var(--primary-light);
        }
        
        .portfolio-item:hover .video-badge,
        .portfolio-item:active .video-badge {
            opacity: 1;
            transform: translateX(0);
        }
        
        /* Services Section - Mobile Optimized */
        .services {
            padding: var(--section-padding);
            background-color: var(--primary-dark);
            transition: var(--transition);
        }
        
        body.light-mode .services {
            background-color: var(--primary-light);
        }
        
        .services-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        @media (min-width: 768px) {
            .services-container {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 40px;
            }
        }
        
        @media (min-width: 992px) {
            .services-container {
                gap: 50px;
            }
        }
        
        .service-card {
            background-color: var(--secondary-dark);
            border-radius: 5px;
            padding: 35px 25px;
            text-align: center;
            transition: var(--transition);
            opacity: 0;
            transform: translateY(30px);
            box-shadow: var(--card-shadow-dark);
            position: relative;
            overflow: hidden;
        }
        
        @media (min-width: 768px) {
            .service-card {
                padding: 40px 30px;
                transform: translateY(40px);
            }
        }
        
        @media (min-width: 992px) {
            .service-card {
                padding: 50px 40px;
            }
        }
        
        body.light-mode .service-card {
            background-color: var(--secondary-light);
            box-shadow: var(--card-shadow);
        }
        
        .service-card.in-view {
            animation: fadeUp 0.8s forwards;
        }
        
        .service-card:nth-child(2) { animation-delay: 0.1s; }
        .service-card:nth-child(3) { animation-delay: 0.2s; }
        
        .service-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: var(--accent);
            transform: scaleX(0);
            transition: var(--transition);
            transform-origin: left;
        }
        
        body.light-mode .service-card:before {
            background-color: var(--accent-light-mode);
        }
        
        .service-card:hover, .service-card:active {
            transform: translateY(-10px);
        }
        
        @media (min-width: 768px) {
            .service-card:hover, .service-card:active {
                transform: translateY(-20px);
            }
        }
        
        .service-card:hover:before, .service-card:active:before {
            transform: scaleX(1);
        }
        
        .service-icon {
            width: 70px;
            height: 70px;
            background-color: rgba(193, 154, 107, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 28px;
            color: var(--accent);
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            .service-icon {
                width: 80px;
                height: 80px;
                font-size: 32px;
                margin-bottom: 30px;
            }
        }
        
        @media (min-width: 992px) {
            .service-icon {
                width: 90px;
                height: 90px;
                font-size: 36px;
            }
        }
        
        body.light-mode .service-icon {
            background-color: rgba(139, 115, 85, 0.1);
            color: var(--accent-light-mode);
        }
        
        .service-card:hover .service-icon,
        .service-card:active .service-icon {
            transform: rotate(15deg) scale(1.1);
        }
        
        .service-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        @media (min-width: 768px) {
            .service-card h3 {
                font-size: 28px;
                margin-bottom: 20px;
            }
        }
        
        .service-features {
            list-style: none;
            text-align: left;
            margin-bottom: 25px;
            padding-left: 15px;
        }
        
        @media (min-width: 768px) {
            .service-features {
                margin-bottom: 30px;
                padding-left: 20px;
            }
        }
        
        .service-features li {
            margin-bottom: 10px;
            position: relative;
            padding-left: 25px;
            font-size: 14px;
        }
        
        @media (min-width: 768px) {
            .service-features li {
                margin-bottom: 12px;
                padding-left: 30px;
                font-size: 16px;
            }
        }
        
        .service-features li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--accent);
            font-weight: bold;
            font-size: 16px;
        }
        
        body.light-mode .service-features li:before {
            color: var(--accent-light-mode);
        }
        
        .price {
            font-size: 32px;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 25px;
            line-height: 1;
        }
        
        @media (min-width: 768px) {
            .price {
                font-size: 42px;
                margin-bottom: 30px;
            }
        }
        
        body.light-mode .price {
            color: var(--accent-light-mode);
        }
        
        .price span {
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: var(--text-gray-dark);
            display: block;
            margin-top: 5px;
        }
        
        @media (min-width: 768px) {
            .price span {
                font-size: 18px;
                display: inline;
                margin-top: 0;
                margin-left: 5px;
            }
        }
        
        body.light-mode .price span {
            color: var(--text-gray-light);
        }
        
        /* About Section - Mobile Optimized */
        .about {
            padding: var(--section-padding);
            background-color: var(--secondary-dark);
            transition: var(--transition);
        }
        
        body.light-mode .about {
            background-color: var(--secondary-light);
        }
        
        .about-content {
            display: flex;
            flex-direction: column;
            gap: 50px;
            align-items: center;
        }
        
        @media (min-width: 992px) {
            .about-content {
                flex-direction: row;
                gap: 80px;
            }
        }
        
        .about-img {
            position: relative;
            opacity: 0;
            transform: translateY(40px);
            width: 100%;
            max-width: 500px;
            order: 2;
        }
        
        @media (min-width: 992px) {
            .about-img {
                transform: translateX(-50px);
                order: 1;
                width: 50%;
            }
        }
        
        .about-img.in-view {
            animation: fadeUp 1s forwards;
        }
        
        @media (min-width: 992px) {
            .about-img.in-view {
                animation: fadeLeft 1s forwards;
            }
        }
        
        .about-img img {
            width: 100%;
            border-radius: 5px;
            box-shadow: var(--card-shadow-dark);
        }
        
        body.light-mode .about-img img {
            box-shadow: var(--card-shadow);
        }
        
        .about-img:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border: 3px solid var(--accent);
            border-radius: 5px;
            top: 15px;
            left: 15px;
            z-index: -1;
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            .about-img:before {
                top: 20px;
                left: 20px;
                border-width: 4px;
            }
        }
        
        @media (min-width: 992px) {
            .about-img:before {
                top: 30px;
                left: 30px;
                border-width: 5px;
            }
        }
        
        body.light-mode .about-img:before {
            border-color: var(--accent-light-mode);
        }
        
        .about-img:hover:before, .about-img:active:before {
            top: 10px;
            left: 10px;
        }
        
        @media (min-width: 768px) {
            .about-img:hover:before,
            .about-img:active:before {
                top: 15px;
                left: 15px;
            }
        }
        
        @media (min-width: 992px) {
            .about-img:hover:before,
            .about-img:active:before {
                top: 20px;
                left: 20px;
            }
        }
        
        .about-text {
            opacity: 0;
            transform: translateY(40px);
            width: 100%;
            order: 1;
        }
        
        @media (min-width: 992px) {
            .about-text {
                transform: translateX(50px);
                order: 2;
                width: 50%;
            }
        }
        
        .about-text.in-view {
            animation: fadeUp 1s forwards 0.3s;
        }
        
        @media (min-width: 992px) {
            .about-text.in-view {
                animation: fadeRight 1s forwards 0.3s;
            }
        }
        
        .about-text h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        
        @media (min-width: 768px) {
            .about-text h2 {
                font-size: 48px;
                margin-bottom: 30px;
            }
        }
        
        @media (min-width: 992px) {
            .about-text h2 {
                font-size: 56px;
            }
        }
        
        .about-text p {
            margin-bottom: 20px;
            color: var(--text-gray-dark);
            font-size: 16px;
        }
        
        @media (min-width: 768px) {
            .about-text p {
                font-size: 18px;
                margin-bottom: 25px;
            }
        }
        
        body.light-mode .about-text p {
            color: var(--text-gray-light);
        }
        
        .signature {
            font-family: 'Cormorant Garamond', serif;
            font-size: 28px;
            color: var(--accent);
            margin-top: 30px;
            font-style: italic;
        }
        
        @media (min-width: 768px) {
            .signature {
                font-size: 32px;
                margin-top: 40px;
            }
        }
        
        body.light-mode .signature {
            color: var(--accent-light-mode);
        }
        
        /* Footer - Mobile Optimized */
        footer {
            background-color: var(--primary-dark);
            padding: 70px 0 30px;
            text-align: center;
            transition: var(--transition);
        }
        
        @media (min-width: 768px) {
            footer {
                padding: 100px 0 50px;
            }
        }
        
        body.light-mode footer {
            background-color: var(--primary-light);
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px;
        }
        
        @media (min-width: 768px) {
            .footer-content {
                margin-bottom: 60px;
            }
        }
        
        .footer-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 25px;
        }
        
        @media (min-width: 768px) {
            .footer-logo {
                font-size: 42px;
                margin-bottom: 30px;
            }
        }
        
        body.light-mode .footer-logo {
            color: var(--accent-light-mode);
        }
        
        .footer-logo span {
            color: var(--text-dark);
        }
        
        body.light-mode .footer-logo span {
            color: var(--text-light);
        }
        
        .footer-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            list-style: none;
            margin-bottom: 30px;
            gap: 15px;
        }
        
        @media (min-width: 576px) {
            .footer-links {
                gap: 20px;
            }
        }
        
        @media (min-width: 768px) {
            .footer-links {
                margin-bottom: 40px;
                gap: 0;
            }
        }
        
        .footer-links li {
            margin: 0;
        }
        
        @media (min-width: 768px) {
            .footer-links li {
                margin: 0 25px;
            }
        }
        
        .footer-links a {
            color: var(--text-gray-dark);
            text-decoration: none;
            transition: var(--transition);
            font-size: 15px;
            padding: 5px 0;
            display: block;
        }
        
        @media (min-width: 768px) {
            .footer-links a {
                font-size: 16px;
            }
        }
        
        body.light-mode .footer-links a {
            color: var(--text-gray-light);
        }
        
        .footer-links a:hover, .footer-links a:active {
            color: var(--accent);
        }
        
        body.light-mode .footer-links a:hover,
        body.light-mode .footer-links a:active {
            color: var(--accent-light-mode);
        }
        
        .social-icons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 30px;
            gap: 10px;
        }
        
        @media (min-width: 768px) {
            .social-icons {
                margin-bottom: 40px;
                gap: 12px;
            }
        }
        
        .social-icons a {
            width: 45px;
            height: 45px;
            background-color: var(--secondary-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            font-size: 18px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            -webkit-tap-highlight-color: transparent;
        }
        
        @media (min-width: 768px) {
            .social-icons a {
                width: 55px;
                height: 55px;
                font-size: 20px;
            }
        }
        
        body.light-mode .social-icons a {
            background-color: var(--secondary-light);
            color: var(--text-light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .social-icons a:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--accent);
            border-radius: 50%;
            transform: scale(0);
            transition: var(--transition);
        }
        
        body.light-mode .social-icons a:before {
            background-color: var(--accent-light-mode);
        }
        
        .social-icons a:hover:before, .social-icons a:active:before {
            transform: scale(1);
        }
        
        .social-icons a i {
            position: relative;
            z-index: 1;
        }
        
        .social-icons a:hover, .social-icons a:active {
            color: var(--primary-dark);
            transform: translateY(-3px);
        }
        
        body.light-mode .social-icons a:hover,
        body.light-mode .social-icons a:active {
            color: var(--primary-light);
        }
        
        .contact-info {
            margin-bottom: 25px;
            color: var(--text-gray-dark);
            font-size: 16px;
            line-height: 1.6;
            padding: 0 10px;
        }
        
        @media (min-width: 768px) {
            .contact-info {
                margin-bottom: 30px;
                font-size: 18px;
                padding: 0;
            }
        }
        
        body.light-mode .contact-info {
            color: var(--text-gray-light);
        }
        
        .contact-info a {
            color: var(--accent);
            text-decoration: none;
            word-break: break-word;
        }
        
        body.light-mode .contact-info a {
            color: var(--accent-light-mode);
        }
        
        .copyright {
            color: var(--text-gray-dark);
            font-size: 14px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .copyright {
                font-size: 15px;
                padding-top: 40px;
            }
        }
        
        body.light-mode .copyright {
            color: var(--text-gray-light);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        /* Animations */
        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeUpScale {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes fadeLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeRight {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Touch Device Optimizations */
        @media (hover: none) and (pointer: coarse) {
            .portfolio-item:hover .portfolio-overlay,
            .service-card:hover,
            .btn:hover,
            .filter-btn:hover,
            .nav-links a:hover,
            .social-icons a:hover {
                transform: none;
            }
            
            .portfolio-item:active .portfolio-overlay,
            .service-card:active,
            .btn:active,
            .filter-btn:active,
            .nav-links a:active,
            .social-icons a:active {
                transform: translateY(-3px) scale(0.98);
            }
            
            .portfolio-item:hover .portfolio-img,
            .portfolio-item:active .portfolio-img {
                transform: scale(1.05);
            }
            
            .portfolio-overlay {
                opacity: 0.8;
            }
            
            .btn:hover:before,
            .btn:active:before {
                left: 0;
                background-color: var(--primary-dark);
            }
            
            body.light-mode .btn:hover:before,
            body.light-mode .btn:active:before {
                background-color: var(--primary-light);
            }
        }
        
        /* Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .btn, .theme-toggle, .service-icon, .video-badge {
                border: 2px solid currentColor;
            }
        }
    </style>
</head>
<body>
    <!-- Theme Toggle -->
    <div class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
    </div>

    <!-- Header & Navigation -->
    <header id="header">
        <div class="container">
            <nav>
                <a href="#" class="logo"><i class="fas fa-camera-retro"></i>Faheem <span>Photography</span></a>
                <div class="menu-overlay" id="menuOverlay"></div>
                <ul class="nav-links" id="navLinks">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#portfolio">Portfolio</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg"></div>
        <div class="container">
            <div class="hero-content">
                <h1>Visual Stories Through <span>Photography & Film</span></h1>
                <p>Capturing life's most precious moments with artistic vision. Based in Dubai, serving clients worldwide for weddings, commercial projects, and cinematic storytelling.</p>
                <a href="#portfolio" class="btn">Explore Our Work</a>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio" id="portfolio">
        <div class="container">
            <div class="section-title">
                <h2>Our Portfolio</h2>
                <p>A blend of photography and cinematic films that capture emotions and tell compelling stories</p>
            </div>
            
            <div class="portfolio-filter">
                <button class="filter-btn active" data-filter="all">All Work</button>
                <button class="filter-btn" data-filter="photography">Photography</button>
                <button class="filter-btn" data-filter="films">Films</button>
                <button class="filter-btn" data-filter="wedding">Wedding</button>
                <button class="filter-btn" data-filter="commercial">Commercial</button>
            </div>
            
            <div class="portfolio-grid">
                <div class="portfolio-item" data-category="photography wedding">
                    <img src="{{ asset('photo.jpg') }}" alt="Wedding Photography" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Desert Wedding</h3>
                        <p>Luxury wedding at Al Maha Resort</p>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="films commercial">
                    <img src="{{ asset('photo.jpg') }}" alt="Commercial Film" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Luxury Brand Film</h3>
                        <p>Cinematic commercial for Emirates</p>
                        <div class="video-badge">FILM</div>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="photography commercial">
                    <img src="{{ asset('photo.jpg') }}" alt="Commercial Photography" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Product Campaign</h3>
                        <p>Luxury watch advertisement series</p>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="films wedding">
                    <img src="{{ asset('photo.jpg') }}" alt="Wedding Film" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Wedding Cinematic</h3>
                        <p>Feature film for Dubai royal wedding</p>
                        <div class="video-badge">FILM</div>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="photography">
                    <img src="{{ asset('photo.jpg') }}" alt="Portrait Photography" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Desert Portrait Series</h3>
                        <p>Editorial fashion in the Arabian desert</p>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="films commercial">
                    <img src="{{ asset('photo.jpg') }}" alt="Corporate Film" class="portfolio-img">
                    <div class="portfolio-overlay">
                        <h3>Corporate Documentary</h3>
                        <p>Emaar's 20-year anniversary film</p>
                        <div class="video-badge">FILM</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services & Pricing Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-title">
                <h2>Services & Pricing</h2>
                <p>Comprehensive photography and film packages tailored to your vision</p>
            </div>
            
            <div class="services-container">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-ring"></i>
                    </div>
                    <h3>Wedding Package</h3>
                    <p>Full coverage of your special day with both photography and cinematic film.</p>
                    <ul class="service-features">
                        <li>10-hour coverage with two photographers</li>
                        <li>Cinematic wedding film (5-7 minutes)</li>
                        <li>Online gallery with 500+ edited photos</li>
                        <li>Engagement session included</li>
                        <li>Premium leather album</li>
                    </ul>
                    <div class="price">$4,200 <span>starting from</span></div>
                    <a href="#contact" class="btn">Book Now</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3>Commercial Films</h3>
                    <p>Professional cinematic films for brands, products, and corporate stories.</p>
                    <ul class="service-features">
                        <li>Concept development & storyboarding</li>
                        <li>Professional 4K cinematography</li>
                        <li>Color grading & sound design</li>
                        <li>Motion graphics & animation</li>
                        <li>Multiple deliverable formats</li>
                    </ul>
                    <div class="price">$2,500 <span>per day</span></div>
                    <a href="#contact" class="btn">Book Now</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Photography Sessions</h3>
                    <p>Portrait, family, and commercial photography sessions.</p>
                    <ul class="service-features">
                        <li>2-hour professional session</li>
                        <li>Studio or location shooting</li>
                        <li>Professional lighting setup</li>
                        <li>50+ edited high-res images</li>
                        <li>Online gallery delivery</li>
                    </ul>
                    <div class="price">$650 <span>per session</span></div>
                    <a href="#contact" class="btn">Book Now</a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="section-title">
                <h2>About Faheem</h2>
                <p>The artist behind the lens</p>
            </div>
            
            <div class="about-content">
                <div class="about-img">
                    <img src="{{ asset('photo.jpg') }}" alt="Faheem - Photographer & Filmmaker">
                </div>
                <div class="about-text">
                    <h2>Creating Visual Poetry Since 2010</h2>
                    <p>With over a decade of experience, I specialize in transforming moments into timeless visual stories. My work spans across wedding photography, commercial campaigns, and cinematic films.</p>
                    <p>Based in Dubai, I've had the privilege of working with clients across the Middle East, Europe, and Asia. My approach combines technical expertise with artistic vision to create imagery that resonates emotionally.</p>
                    <p>Whether it's capturing the raw emotion of a wedding day or telling a brand's story through film, I believe in creating work that stands the test of time and leaves a lasting impression.</p>
                    <div class="signature">Faheem Ahmed</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">Faheem <span>Photography & Films</span></div>
                <ul class="footer-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#portfolio">Portfolio</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-vimeo-v"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-pinterest-p"></i></a>
                </div>
                <div class="contact-info">
                    <p>Based in Dubai, UAE | Available Worldwide</p>
                    <p>Email: <a href="mailto:hello@faheemphotography.com">hello@faheemphotography.com</a></p>
                    <p>Phone: +971 50 123 4567</p>
                </div>
                <a href="mailto:hello@faheemphotography.com" class="btn">Start Your Project</a>
            </div>
            <div class="copyright">
                &copy; 2023 Faheem Photography & Films. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // DOM Elements
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;
        const themeIcon = themeToggle.querySelector('i');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');
        const menuOverlay = document.getElementById('menuOverlay');
        const header = document.getElementById('header');

        // Check for saved theme or prefer-color-scheme
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const currentTheme = localStorage.getItem('theme');
        
        if (currentTheme === 'light' || (!currentTheme && prefersDarkScheme.matches)) {
            body.classList.add('light-mode');
            themeIcon.className = 'fas fa-sun';
        }
        
        // Theme Toggle
        themeToggle.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            
            if (body.classList.contains('light-mode')) {
                themeIcon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'light');
            } else {
                themeIcon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'dark');
            }
        });

        // Mobile Menu Toggle
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            mobileMenuBtn.innerHTML = navLinks.classList.contains('active') 
                ? '<i class="fas fa-times"></i>' 
                : '<i class="fas fa-bars"></i>';
            
            // Prevent body scroll when menu is open
            if (navLinks.classList.contains('active')) {
                body.style.overflow = 'hidden';
            } else {
                body.style.overflow = '';
            }
        });

        // Close menu when clicking overlay
        menuOverlay.addEventListener('click', () => {
            navLinks.classList.remove('active');
            menuOverlay.classList.remove('active');
            mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            body.style.overflow = '';
        });

        // Close menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                menuOverlay.classList.remove('active');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                body.style.overflow = '';
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Portfolio filtering
        const filterButtons = document.querySelectorAll('.filter-btn');
        const portfolioItems = document.querySelectorAll('.portfolio-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Filter items
                const filter = button.getAttribute('data-filter');
                
                portfolioItems.forEach(item => {
                    const categories = item.getAttribute('data-category').split(' ');
                    
                    if (filter === 'all' || categories.includes(filter)) {
                        item.style.display = 'block';
                        // Trigger reflow for animation
                        void item.offsetWidth;
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0) scale(1)';
                    } else {
                        item.style.opacity = '0';
                        item.style.transform = 'translateY(30px) scale(0.95)';
                        setTimeout(() => {
                            item.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.querySelectorAll('.section-title, .portfolio-filter, .portfolio-item, .service-card, .about-img, .about-text').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Touch device optimizations
        if ('ontouchstart' in window) {
            // Add touch-specific classes
            body.classList.add('touch-device');
            
            // Improve touch feedback
            document.querySelectorAll('.btn, .filter-btn, .social-icons a').forEach(element => {
                element.addEventListener('touchstart', function() {
                    this.classList.add('touch-active');
                });
                
                element.addEventListener('touchend', function() {
                    this.classList.remove('touch-active');
                });
            });
        }

        // Load initial animations
        window.addEventListener('load', () => {
            document.querySelector('.hero-content').style.animationPlayState = 'running';
            
            // Add loaded class for any post-load animations
            setTimeout(() => {
                body.classList.add('loaded');
            }, 500);
        });

        // Handle orientation change
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);

        window.addEventListener('resize', () => {
            let vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        });

        // Handle iOS viewport height issue
        if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
            document.documentElement.style.setProperty('--vh', '1vh');
        }
    </script>
</body>
</html>