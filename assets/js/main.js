function filterActivities() {
    const search = document.getElementById('search-input').value;
    const category = document.getElementById('category-filter').value;
    const list = document.getElementById('activity-list');

    // Fetch updated results from our API
    fetch(`api/search_activities.php?search=${search}&category=${category}`)
        .then(response => response.text())
        .then(data => {
            list.innerHTML = data;
        })
        .catch(error => console.error('Error fetching activities:', error));
}