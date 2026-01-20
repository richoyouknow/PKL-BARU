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
                    <h1 class="mb-5"><span class="text-uppercase text-primary bg-light px-2">Profile</span> koperasi</h1>
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
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="iStudio-1.0.0/img/project-4.jpg" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-white">Living Room</h4>
                                    <small class="text-white">33 Projects</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.6s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="iStudio-1.0.0/img/project-5.jpg" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-white">Furniture</h4>
                                    <small class="text-white">87 Projects</small>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.7s">
                            <div class="project-item position-relative overflow-hidden">
                                <img class="img-fluid w-100" src="iStudio-1.0.0/img/project-6.jpg" alt="">
                                <a class="project-overlay text-decoration-none" href="#!">
                                    <h4 class="text-white">Rennovation</h4>
                                    <small class="text-white">69 Projects</small>
                                </a>
                            </div>
                        </div>
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
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="team-item position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="iStudio-1.0.0/img/team-1.jpg" alt="">
                        <div class="team-overlay">
                            <small class="mb-2">Architect</small>
                            <h4 class="lh-base text-light">Boris Johnson</h4>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                    <div class="team-item position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="iStudio-1.0.0/img/team-2.jpg" alt="">
                        <div class="team-overlay">
                            <small class="mb-2">Architect</small>
                            <h4 class="lh-base text-light">Donald Pakura</h4>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.5s">
                    <div class="team-item position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="iStudio-1.0.0/img/team-3.jpg" alt="">
                        <div class="team-overlay">
                            <small class="mb-2">Architect</small>
                            <h4 class="lh-base text-light">Bradley Gordon</h4>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.7s">
                    <div class="team-item position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="iStudio-1.0.0/img/team-4.jpg" alt="">
                        <div class="team-overlay">
                            <small class="mb-2">Architect</small>
                            <h4 class="lh-base text-light">Alexander Bell</h4>
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="btn btn-outline-primary btn-sm-square border-2 me-2" href="#!">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
