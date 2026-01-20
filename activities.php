<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<main class="container">
    <div class="activities-layout">
        <aside class="filters">
            <div class="filter-card">
                <h3>Filter Activities</h3>
                <div class="filter-group">
                    <label>Search Title</label>
                    <div class="input-with-icon">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search-input" placeholder="e.g. Yoga..." onkeyup="filterActivities()">
                    </div>
                </div>
                <div class="filter-group">
                    <label>Category</label>
                    <div class="input-with-icon">
                        <i class="fas fa-tag"></i>
                        <select id="category-filter" onchange="filterActivities()">
                            <option value="">All Categories</option>
                            <option value="Social">Social</option>
                            <option value="Wellness">Wellness</option>
                            <option value="Culture">Culture</option>
                        </select>
                    </div>
                </div>
                <button class="btn-clear" onclick="clearFilters()">Clear All</button>
            </div>
        </aside>

        <section class="activity-results">
            <div id="activity-list" class="activity-grid">
                </div>
        </section>
    </div>
</main>

<script src="assets/js/main.js"></script>
<script>document.addEventListener('DOMContentLoaded', filterActivities);</script>

<?php include 'includes/footer.php'; ?>