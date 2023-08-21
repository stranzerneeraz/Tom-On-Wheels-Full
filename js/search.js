$(document).ready(function () {
    $("#searchForm").submit(function (event) {
        event.preventDefault(); // Prevent form submission

        var searchQuery = $("input[name='searchQuery']").val();

        // Update browser history
        var newUrl = updateQueryStringParameter(window.location.href, 'searchQuery', searchQuery);
        console.log(newUrl);
        history.pushState({}, '', newUrl);

        // Check if the user is on the menu.php page
        if (window.location.pathname.includes("menu.php")) {
            // Fetch and display search results on the same page
            fetchSearchResults(searchQuery);
        } else {
            // Redirect to menu.php with the search query as a parameter
            window.location.href = "menu.php?searchQuery=" + encodeURIComponent(searchQuery);
        }
    });

    // ...

    function fetchSearchResults(searchQuery) {
        $.ajax({
            url: "search.php",
            method: "GET",
            data: { searchQuery: searchQuery },
            success: function (response) {
                $("#filteredResults").html(response);
            },
            error: function (xhr, status, error) {
                console.error(error); // For debugging
            }
        });
    }

    // Function to update a URL parameter
    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        return uri + separator + key + "=" + value;
    }

    // ...
});
