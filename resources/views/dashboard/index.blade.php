@extends('layouts.app')

@section('title', 'Mantiq - Dashboard')

@section('content')

<style>
    /* Dashboard Main */
    .dashboard-page {
        padding-bottom: 100px;
    }

    .dashboard-container {
        max-width: 1150px;
        margin: 0 auto;
        padding: 30px 22px;
    }

    /* Welcome Section */
    .dashboard-welcome {
        background: linear-gradient(135deg, #800080, #a64ca6);
        color: #fff;
        padding: 38px;
        border-radius: 22px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        box-shadow: 0 10px 30px rgba(128, 0, 128, 0.13);
    }

    .dashboard-welcome h1 {
        color: #fff;
        font-size: 30px;
        margin: 0 0 12px;
        font-weight: 700;
    }

    .dashboard-welcome p {
        color: rgba(255,255,255,0.88);
        margin: 0;
        line-height: 1.7;
        max-width: 570px;
    }

    .welcome-button {
        display: inline-block;
        background: #fff;
        color: #800080;
        padding: 12px 22px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        white-space: nowrap;
        transition: 0.2s ease;
    }

    .welcome-button:hover {
        background: #f4e8f4;
        transform: translateY(-2px);
    }

    /* Section Headings */
    .dashboard-section-heading {
        margin: 35px 0 18px;
    }

    .dashboard-section-heading h2 {
        color: #29212d;
        font-size: 23px;
        margin: 0 0 6px;
        font-weight: 700;
    }

    .dashboard-section-heading p {
        color: #777;
        font-size: 14px;
        margin: 0;
    }

    /* Statistics */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .dashboard-stat-card {
        background: #fff;
        border: 1px solid #eee5f0;
        border-radius: 16px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.035);
    }

    .dashboard-stat-icon {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        background: #f5eafa;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dashboard-stat-icon img {
        width: 26px;
        height: 26px;
        object-fit: contain;
    }

    .dashboard-stat-card h3 {
        color: #800080;
        font-size: 25px;
        margin: 0 0 4px;
        font-weight: 700;
    }

    .dashboard-stat-card span {
        color: #777;
        font-size: 13px;
    }

    /* Learning Cards */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .dashboard-item {
        background: #fff;
        border: 1px solid #eee5f0;
        border-radius: 18px;
        padding: 24px;
        position: relative;
        box-shadow: 0 5px 18px rgba(0,0,0,0.035);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(128,0,128,0.09);
    }

    .dashboard-card {
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        color: #29212d;
    }

    .dashboard-icon {
        width: 62px;
        height: 62px;
        flex-shrink: 0;
        background: #f7eff9;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dashboard-icon img {
        width: 39px;
        height: 39px;
        object-fit: contain;
    }

    .dashboard-card h2 {
        font-size: 19px;
        margin: 0;
        color: #800080;
        font-weight: 700;
    }

    .dashboard-item-description {
        color: #777;
        font-size: 14px;
        margin: 17px 0 14px;
    }

    .dashboard-item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-top: 13px;
        border-top: 1px solid #f0eaf1;
    }

    .dashboard-item-count {
        color: #800080;
        background: #f7eff9;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .dashboard-item-link {
        color: #800080;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
    }

    .dashboard-item-link:hover {
        text-decoration: underline;
    }

    /* About Section */
    .dashboard-about {
        background: #fff;
        border: 1px solid #eee5f0;
        border-radius: 18px;
        padding: 26px;
        margin-top: 35px;
    }

    .dashboard-about h2 {
        color: #800080;
        font-size: 21px;
        margin: 0 0 12px;
    }

    .dashboard-about p {
        color: #777;
        line-height: 1.8;
        margin: 0;
    }

    /* Logout */
    .dashboard-logout {
        text-align: center;
        margin-top: 30px;
    }

    .dashboard-logout button {
        border: 1px solid #e8d8e8;
        background: #fff;
        color: #800080;
        cursor: pointer;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 10px;
        transition: 0.2s ease;
    }

    .dashboard-logout button:hover {
        background: #800080;
        color: #fff;
    }

    /* Bottom Navigation */
    .dashboard-nav {
        z-index: 100;
    }

    .dashboard-nav a {
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 700px) {

        .dashboard-container {
            padding: 20px 15px;
        }

        .dashboard-welcome {
            padding: 25px 20px;
            flex-direction: column;
            align-items: flex-start;
        }

        .dashboard-welcome h1 {
            font-size: 24px;
        }

        .dashboard-stats {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .dashboard-item {
            padding: 20px;
        }

        .dashboard-section-heading h2 {
            font-size: 20px;
        }
    }
</style>

<div class="dashboard-page">

    <main class="dashboard-container">

        <!-- Welcome Section -->
        <section class="dashboard-welcome">

            <div>
                <h1>Welcome {{ $user->name }}! 👋</h1>

                <p>
                    Welcome to Mantiq! Explore learning resources,
                    discover new skills, and continue your learning journey.
                </p>
            </div>

            <a href="#learning-hubs" class="welcome-button">
                Explore Learning
            </a>

        </section>

        @include('dashboard.partials.continue')


        <!-- Learning Statistics -->
        <section>

            <div class="dashboard-section-heading">
                <h2>Your Learning Overview</h2>
                <p>A quick look at the available learning resources.</p>
            </div>

            <div class="dashboard-stats">

                <article class="dashboard-stat-card">

                    <div class="dashboard-stat-icon">
                        <img src="{{ asset('images/mantiq/tech-track.svg') }}"
                             alt="">
                    </div>

                    <div>
                        <h3>{{ $techContents->count() }}</h3>
                        <span>Published Tech Items</span>
                    </div>

                </article>


                <article class="dashboard-stat-card">

                    <div class="dashboard-stat-icon">
                        <img src="{{ asset('images/mantiq/b2b-route.svg') }}"
                             alt="">
                    </div>

                    <div>
                        <h3>{{ $businessContents->count() }}</h3>
                        <span>Published Business Items</span>
                    </div>

                </article>


                <article class="dashboard-stat-card">

                    <div class="dashboard-stat-icon">
                        <img src="{{ asset('images/mantiq/buildcast.svg') }}"
                             alt="">
                    </div>

                    <div>
                        <h3>{{ $podcasts->count() }}</h3>
                        <span>Published Podcast Episodes</span>
                    </div>

                </article>

            </div>

        </section>


        <!-- Learning Hubs -->
        <section id="learning-hubs">

            <div class="dashboard-section-heading">
                <h2>Explore Learning</h2>
                <p>Choose a learning path and start exploring.</p>
            </div>


            <div class="dashboard-grid">

                <!-- Tech Track -->
                <article class="dashboard-item">

                    <a href="{{ route('learning.index', ['track' => 'tech']) }}"
                       class="dashboard-card">

                        <div class="dashboard-icon">
                            <img src="{{ asset('images/mantiq/tech-track.svg') }}"
                                 alt="Tech Track">
                        </div>

                        <h2>Tech Track</h2>

                    </a>

                    <p class="dashboard-item-description">
                        Explore technical courses and develop your programming
                        and technology skills.
                    </p>

                    <div class="dashboard-item-footer">

                        <span class="dashboard-item-count">
                            {{ $techContents->count() }} Published Items
                        </span>

                        <a href="{{ route('learning.index', ['track' => 'tech']) }}"
                           class="dashboard-item-link">
                            Explore Track →
                        </a>

                    </div>

                </article>


                <!-- B2B Route -->
                <article class="dashboard-item">

                    <a href="{{ route('learning.index', ['track' => 'business']) }}"
                       class="dashboard-card">

                        <div class="dashboard-icon">
                            <img src="{{ asset('images/mantiq/b2b-route.svg') }}"
                                 alt="B2B Route">
                        </div>

                        <h2>B2B Route</h2>

                    </a>

                    <p class="dashboard-item-description">
                        Discover business resources and build your professional
                        and entrepreneurial skills.
                    </p>

                    <div class="dashboard-item-footer">

                        <span class="dashboard-item-count">
                            {{ $businessContents->count() }} Published Items
                        </span>

                        <a href="{{ route('learning.index', ['track' => 'business']) }}"
                           class="dashboard-item-link">
                            Explore Route →
                        </a>

                    </div>

                </article>


                <!-- BuildCast -->
                <article class="dashboard-item">

                    <a href="{{ route('learning.podcasts') }}"
                       class="dashboard-card">

                        <div class="dashboard-icon">
                            <img src="{{ asset('images/mantiq/buildcast.svg') }}"
                                 alt="BuildCast">
                        </div>

                        <h2>BuildCast</h2>

                    </a>

                    <p class="dashboard-item-description">
                        Listen to useful discussions, explore new ideas,
                        and gain knowledge through podcasts.
                    </p>

                    <div class="dashboard-item-footer">

                        <span class="dashboard-item-count">
                            {{ $podcasts->count() }} Published Episodes
                        </span>

                        <a href="{{ route('learning.podcasts') }}"
                           class="dashboard-item-link">
                            Explore Podcasts →
                        </a>

                    </div>

                </article>


                <!-- About Me -->
                <article class="dashboard-item">

                    <a href="#about-me" class="dashboard-card">

                        <div class="dashboard-icon">
                            <img src="{{ asset('images/mantiq/about-me.svg') }}"
                                 alt="About Mantiq">
                        </div>

                        <h2>About Mantiq</h2>

                    </a>

                    <p class="dashboard-item-description">
                        Learn more about Mantiq and our mission to make
                        learning accessible and meaningful.
                    </p>

                    <div class="dashboard-item-footer">

                        <span class="dashboard-item-count">
                            Platform Information
                        </span>

                        <a href="#about-me" class="dashboard-item-link">
                            Learn More →
                        </a>

                    </div>

                </article>

            </div>

        </section>


        <!-- About Mantiq -->
        <section id="about-me" class="dashboard-about">

            <h2>About Mantiq</h2>

            <p>
                Welcome to Mantiq, your learning and development platform.
                Our goal is to help you explore knowledge, develop new skills,
                and continue growing through valuable learning resources.
            </p>

        </section>


        <!-- Logout -->
        <div class="dashboard-logout">

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit">
                    Logout
                </button>

            </form>

        </div>

    </main>


    <!-- Bottom Navigation -->
 
<nav class="dashboard-nav">

    <a href="{{ route('dashboard') }}" class="active">
        <img class="nav-icon"
             src="{{ asset('images/mantiq/home.svg') }}"
             alt="">
        <span>Home</span>
    </a>

    <a href="{{ route('learning.index', ['track' => 'tech']) }}">
        <img class="nav-icon"
             src="{{ asset('images/mantiq/tech-track.svg') }}"
             alt="">
        <span>Tech</span>
    </a>

    <a href="{{ route('learning.index', ['track' => 'business']) }}">
        <img class="nav-icon"
             src="{{ asset('images/mantiq/b2b-route.svg') }}"
             alt="">
        <span>Business</span>
    </a>

    <a href="{{ route('learning.podcasts') }}">
        <img class="nav-icon"
             src="{{ asset('images/mantiq/buildcast.svg') }}"
             alt="">
        <span>Podcast</span>
    </a>

    <a href="#about-me">
        <img class="nav-icon"
             src="{{ asset('images/mantiq/about-me.svg') }}"
             alt="">
        <span>About</span>
    </a>

</nav>


</div>

@endsection
