<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<main class="container">
    <div class="activities-layout">
        <aside class="filters">
            <h3>Filter Activities</h3>
            <div class="filter-group">
                <input type="text" id="search-input" placeholder="Search by title..." onkeyup="filterActivities()">
            </div>
            <div class="filter-group">
                <label>Category</label>
                <select id="category-filter" onchange="filterActivities()">
                    <option value="">All Categories</option>
                    <option value="Social">Social</option>
                    <option value="Wellness">Wellness</option>
                    <option value="Culture">Culture</option>
                </select>
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