<header class="top-header">
    <div class="header-left">
        <button class="menu-toggle d-lg-none" id="menuToggle">
            <i class="bi bi-list"></i>
        </button>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search anything...">
        </div>
    </div>
    <div class="header-right">
        <button class="header-icon-btn" title="Notifications">
            <i class="bi bi-bell"></i>
            <span class="notification-dot"></span>
        </button>
        <button class="header-icon-btn" title="Messages">
            <i class="bi bi-chat-dots"></i>
        </button>
        <div class="header-divider"></div>
        <div class="header-date">
            <i class="bi bi-calendar3"></i>
            <span>{{ now()->format('D, d M Y') }}</span>
        </div>
    </div>
</header>
