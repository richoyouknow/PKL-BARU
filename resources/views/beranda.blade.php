@php
    $sliders = \App\Models\Slider::where('is_active', true)->orderBy('order')->get();
@endphp

@extends('layout.master')

@section('content')
    <!-- Hero Start -->
    <div class="container-fluid pb-5 hero-header bg-light mb-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center mb-5">
                <div class="col-lg-6">
                    <h1 class="display-1 mb-4 animated slideInRight">Selamat Datang di <span
                            class="text-primary">Koperasi</span>
                    </h1>
                </div>
                <div class="col-lg-6 text-center wow fadeIn" data-wow-delay="0.5s">
                    <img src="denmart.jpeg" class="img-fluid rounded shadow" alt="Header Image">
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Slider Iklan Start -->
    <div class="container-fluid py-5"
        style="background: linear-gradient(135deg, #e7f0ee 0%, #d4e9e5 100%); position: relative; overflow: hidden;">
        <!-- Background Decorative Elements -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 0;">
            <div class="position-absolute"
                style="top: 10%; left: 5%; width: 80px; height: 80px; border-radius: 50%; background: rgba(13, 110, 253, 0.05);">
            </div>
            <div class="position-absolute"
                style="bottom: 15%; right: 8%; width: 120px; height: 120px; border-radius: 50%; background: rgba(255, 107, 53, 0.05);">
            </div>
        </div>

        <div class="container position-relative" style="z-index: 1;">
            <!-- Header Section -->
            <div class="text-center mb-5">
                <h2 class="mb-3 display-5 fw-bold">
                    <span class="text-gradient">Promo & Penawaran</span>
                </h2>
                <p class="text-muted fs-5">Dapatkan penawaran terbaik dari koperasi kami dengan keuntungan maksimal</p>
            </div>

            <!-- Slider Container -->
            <div class="position-relative">
                <!-- Progress Bar -->
                <div class="slider-progress-container mb-4">
                    <div class="slider-progress-bar" id="sliderProgressBar"></div>
                </div>

                <!-- Slider -->
                <div class="owl-carousel iklan-carousel">
                    @forelse($sliders as $slider)
                        <div class="iklan-item">
                            <div class="card-iklan">
                                <!-- Image Container -->
                                <div class="card-iklan-img-container">
                                    <img class="card-iklan-img" src="{{ asset('storage/' . $slider->image) }}"
                                        alt="{{ $slider->title ?? 'Slider ' . $loop->iteration }}" loading="lazy">

                                    <!-- Promotion Badge -->
                                    <div class="promotion-badge">
                                        <span class="badge-text">HOT</span>
                                    </div>

                                    <!-- Overlay Effect -->
                                    <div class="card-iklan-overlay"></div>
                                </div>

                                <!-- Content Container -->
                                <div class="card-iklan-content">
                                    <h5 class="card-iklan-title">{{ $slider->title ?? 'Promo Spesial' }}</h5>
                                    <p class="card-iklan-desc">
                                        {{ $slider->description ?? 'Dapatkan penawaran terbatas untuk member koperasi' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Kondisi ketika tidak ada promo -->
                        <div class="iklan-item">
                            <div class="card-iklan">
                                <div class="card-iklan-img-container">
                                    <img class="card-iklan-img"
                                        src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                        alt="Tidak Ada Promo Saat Ini" loading="lazy">
                                    <div class="promotion-badge" style="background-color: #6c757d;">
                                        <span class="badge-text">INFO</span>
                                    </div>
                                    <div class="card-iklan-overlay"></div>
                                </div>
                                <div class="card-iklan-content">
                                    <h5 class="card-iklan-title">Belum Ada Promo Saat Ini</h5>
                                    <p class="card-iklan-desc">Kami sedang mempersiapkan program promo menarik untuk Anda.
                                        Pantau terus halaman ini untuk mendapatkan penawaran spesial dari koperasi kami.</p>

                                    <!-- Informasi Tambahan -->
                                    <div class="promo-info mt-3">
                                        <div class="info-item mb-2">
                                            <i class="fas fa-calendar-check text-primary me-2"></i>
                                            <span class="small">Promo berikutnya akan datang segera</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Slider Counter -->
                <div class="slider-counter text-center mt-4">
                    <span id="currentSlide" class="fw-bold fs-5 text-primary">1</span>
                    <span class="text-muted mx-2">/</span>
                    <span id="totalSlides" class="text-muted fs-5">3</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles for the Slider -->
    <style>
        /* Text Gradient Effect */
        .text-gradient {
            background: linear-gradient(90deg, var(--bs-primary) 0%, var(--bs-success) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card Iklan Styles */
        .card-iklan {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-iklan:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .card-iklan-img-container {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .card-iklan-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .card-iklan:hover .card-iklan-img {
            transform: scale(1.05);
        }

        .card-iklan-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 60%, rgba(0, 0, 0, 0.4) 100%);
        }

        .promotion-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--bs-primary);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 0.8rem;
            z-index: 2;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .badge-text {
            letter-spacing: 0.5px;
        }

        .card-iklan-content {
            padding: 24px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-iklan-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            font-size: 1.25rem;
        }

        .card-iklan-desc {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            flex-grow: 1;
        }

        /* CTA Button */
        .btn-cta {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(90deg, var(--bs-primary) 0%, #0b5ed7 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-cta:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.3);
        }

        /* Slider Navigation Buttons */
        .btn-slider {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: none;
            color: var(--bs-primary);
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-slider:hover {
            background: var(--bs-primary);
            color: white;
            transform: translateY(-50%) scale(1.1);
        }

        .btn-slider-prev {
            left: -25px;
        }

        .btn-slider-next {
            right: -25px;
        }

        /* Progress Bar */
        .slider-progress-container {
            width: 100%;
            height: 4px;
            background: rgba(0, 0, 0, 0.08);
            border-radius: 2px;
            overflow: hidden;
        }

        .slider-progress-bar {
            height: 100%;
            width: 33.33%;
            background: linear-gradient(90deg, var(--bs-primary) 0%, var(--bs-success) 100%);
            border-radius: 2px;
            transition: width 0.5s ease;
        }

        /* Info Icons */
        .info-icon {
            width: 70px;
            height: 70px;
            background: rgba(13, 110, 253, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .info-icon:hover {
            background: rgba(13, 110, 253, 0.2);
            transform: scale(1.05);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .btn-slider-prev {
                left: 10px;
            }

            .btn-slider-next {
                right: 10px;
            }

            .card-iklan-img-container {
                height: 180px;
            }

            .card-iklan-content {
                padding: 18px;
            }
        }
    </style>

    <!-- JavaScript for Slider Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Owl Carousel
            $('.iklan-carousel').owlCarousel({
                loop: true,
                margin: 30,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    992: {
                        items: 3
                    }
                },
                onInitialized: updateSliderCounter,
                onChanged: updateSliderCounter
            });

            // Custom navigation
            $('.btn-slider-next').click(function() {
                $('.iklan-carousel').trigger('next.owl.carousel');
            });

            $('.btn-slider-prev').click(function() {
                $('.iklan-carousel').trigger('prev.owl.carousel');
            });

            // Update slider counter
            function updateSliderCounter(event) {
                const current = event.item.index + 1;
                const total = event.item.count;

                document.getElementById('currentSlide').textContent = current > total ? 1 : current;
                document.getElementById('totalSlides').textContent = total;

                // Update progress bar
                const progress = (current / total) * 100;
                document.getElementById('sliderProgressBar').style.width = `${progress}%`;
            }

            // Add CSS for owl carousel items
            const style = document.createElement('style');
            style.textContent = `
        .owl-item {
            display: flex;
            justify-content: center;
        }

        .iklan-item {
            padding: 5px;
        }

        .owl-stage {
            display: flex;
            align-items: stretch;
        }
    `;
            document.head.appendChild(style);
        });
    </script>

    <!-- Include Required Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- Slider Iklan End -->

    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="owl-carousel header-carousel about-carousel">
                        <img class="img-fluid rounded" src="iStudio-1.0.0/img/hero-slider-1.jpg" alt="">
                        <img class="img-fluid rounded" src="iStudio-1.0.0/img/hero-slider-2.jpg" alt="">
                        <img class="img-fluid rounded" src="iStudio-1.0.0/img/hero-slider-3.jpg" alt="">
                    </div>
                </div>
                <div class="col-lg-7 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-5"><span class="text-uppercase text-primary bg-light px-2">Profile</span> koperasi
                    </h1>
                    <p class="mb-4">Koperasi Konsumen Daun Emas Nusantara adalah koperasi yang beranggotakan masyarakat
                        dengan tujuan meningkatkan kesejahteraan ekonomi secara bersama-sama. Koperasi ini berlokasi di
                        Jalan Mohammad Husni Thamrin No. 143, RT 004 RW 010, Kecamatan Ajung, Kabupaten Jember, dan
                        menjalankan kegiatan usaha serta pelayanan anggota dalam wilayah Kabupaten Jember, Jawa Timur.</p>
                    <p class="mb-4">Dalam menjalankan aktivitasnya, koperasi berlandaskan Pancasila dan Undang-Undang
                        Dasar 1945, serta memegang teguh nilai-nilai kekeluargaan sebagai dasar utama. Setiap kegiatan usaha
                        dijalankan berdasarkan prinsip koperasi, seperti keanggotaan yang sukarela dan terbuka, pengelolaan
                        secara demokratis, pembagian SHU yang adil, serta kemandirian dalam pengelolaan organisasi.</p>
                    <p class="mb-5">Selain itu, koperasi juga menerapkan prinsip pengembangan seperti pendidikan
                        perkoperasian dan kerja sama antar koperasi. Melalui pemanfaatan sumber daya yang ada, koperasi
                        berkomitmen memberikan pelayanan terbaik kepada anggota dengan tetap mengikuti kaidah ekonomi dan
                        prinsip koperasi yang berlaku.</p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <h6 class="mb-3"><i class="fa fa-check text-primary me-2"></i>Sukarela</h6>
                            <h6 class="mb-0"><i class="fa fa-check text-primary me-2"></i>Terbuka</h6>
                        </div>
                        <div class="col-sm-6">
                            <h6 class="mb-3"><i class="fa fa-check text-primary me-2"></i>Demokratis</h6>
                            <h6 class="mb-0"><i class="fa fa-check text-primary me-2"></i>Kemandirian</h6>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- About End -->


<!-- Project Start -->
    <div class="container-fluid mt-5">
        <div class="container mt-5">
            <div class="row g-0">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex flex-column justify-content-center bg-primary h-100 p-5">
                        <h1 class="text-white mb-5">Galeri Kegiatan <span
                                class="text-uppercase text-primary bg-light px-2">Koperasi</span></h1>
                        <h4 class="text-white mb-0"><span class="display-1"></span> Daun Emas Nusantara</h4>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row g-0">
                        @forelse($activities as $index => $activity)
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="{{ 0.2 + ($index * 0.1) }}s">
                                <div class="project-item position-relative overflow-hidden">
                                    <img class="img-fluid w-100" src="{{ Storage::url($activity->image) }}" alt="{{ $activity->title }}">
                                    <a class="project-overlay text-decoration-none" href="#!">
                                        <h4 class="text-white">{{ $activity->title }}</h4>
                                        <small class="text-white">{{ $activity->project_count }} Projects</small>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.2s">
                                <div class="project-item position-relative overflow-hidden">
                                    <img class="img-fluid w-100" src="iStudio-1.0.0/img/project-1.jpg" alt="">
                                    <a class="project-overlay text-decoration-none" href="#!">
                                        <h4 class="text-white">Kitchen</h4>
                                        <small class="text-white">72 Projects</small>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="project-item position-relative overflow-hidden">
                                    <img class="img-fluid w-100" src="iStudio-1.0.0/img/project-2.jpg" alt="">
                                    <a class="project-overlay text-decoration-none" href="#!">
                                        <h4 class="text-white">Bathroom</h4>
                                        <small class="text-white">67 Projects</small>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.4s">
                                <div class="project-item position-relative overflow-hidden">
                                    <img class="img-fluid w-100" src="iStudio-1.0.0/img/project-3.jpg" alt="">
                                    <a class="project-overlay text-decoration-none" href="#!">
                                        <h4 class="text-white">Bedroom</h4>
                                        <small class="text-white">53 Projects</small>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Project End -->



<!-- Team Start -->
<div class="container-fluid bg-light py-5">
    <div class="container py-5">
        <h1 class="mb-5">Anggota <span class="text-uppercase text-primary bg-light px-2">DEN</span>
        </h1>
        <div class="row g-4">
            @forelse($members as $index => $member)
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="{{ 0.1 + ($index * 0.2) }}s">
                    <div class="team-item position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{{ Storage::url($member->photo) }}" alt="{{ $member->name }}">
                        <div class="team-overlay">
                            <small class="mb-2">{{ $member->position }}</small>
                            <h4 class="lh-base text-light">{{ $member->name }}</h4>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center">Belum ada data anggota.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Team End -->

    <!-- Visi Misi Start -->
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="mb-3"><span class="text-primary">Visi</span> & Misi</h1>
            <p class="text-muted">Koperasi Daun Emas.</p>
        </div>

        <div class="row g-4">
            <!-- VISI -->
            <div class="col-lg-6">
                <div class="p-4 shadow-sm bg-white rounded h-100">
                    <h3 class="text-primary mb-3">Visi</h3>
                    <p>
                        Menjadi koperasi yang mandiri, modern, berkualitas dan dapat meningkatkan kesejahteraan anggota
                    </p>
                </div>
            </div>

            <!-- MISI -->
            <div class="col-lg-6">
                <div class="p-4 shadow-sm bg-white rounded h-100">
                    <h3 class="text-primary mb-3">Misi</h3>
                    <ul class="mb-0">
                        <li>Memberikan layanan prima kepada seluruh anggota dan stakeholder.</li>
                        <li>Menyediakan produk dan jasa yang sesuai kebutuhan anggota dan stakeholder.</li>
                        <li>Menjalankan manajemen organisasi yang transparan dan akuntabel.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Visi Misi End -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".iklan-carousel").owlCarousel({
                loop: true,
                margin: 20,
                nav: false,
                dots: true,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                slideTransition: 'linear',
                autoplaySpeed: 1000,
                smartSpeed: 1000,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    992: {
                        items: 3
                    }
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .iklan-carousel {
            position: relative;
        }

        .iklan-carousel .owl-dots {
            text-align: center;
            margin-top: 20px;
        }

        .iklan-carousel .owl-dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            background: #ddd;
            border-radius: 50%;
            margin: 0 5px;
            transition: all 0.3s;
        }

        .iklan-carousel .owl-dot.active {
            background: var(--primary);
            width: 30px;
            border-radius: 10px;
        }

        .iklan-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .iklan-item:hover img {
            transform: scale(1.02);
        }
    </style>
@endpush
