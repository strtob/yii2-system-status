$(document).ready(function () {

    // Search functionality remains the same
    $('#searchInput').on('keyup', function () {
        var input, filter, table, tr, td, i, j, txtValue;
        input = $(this).val();
        filter = input.toUpperCase();
        table = document.getElementById("systemStatusTable");
        tr = table.getElementsByTagName("tr");
        
        // Loop through all table rows (excluding the first row which may be headers)
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none"; // Default to hidden unless a match is found
            
            // Check each cell in the current row
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = ""; // Show the row if a match is found
                        break; // Exit inner loop if any cell matches the search
                    }
                }
            }
        }
    });
    
    // Sorting functionality with icons directly after column name
    $('#systemStatusTable th').on('click', function () {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("systemStatusTable");
        switching = true;
        dir = $(this).attr('data-order') === 'desc' ? 'asc' : 'desc'; // Toggle sort direction

        // Remove any existing sort icons
        $('#systemStatusTable th i').remove();
        
        // Add sort icon based on the direction directly after the column name
        $(this).append(dir === 'asc' ? '<i class="fa fa-sort-up"></i>' : '<i class="fa fa-sort-down"></i>');
        
        $(this).attr('data-order', dir); // Set new direction
        
        while (switching) {
            switching = false;
            rows = table.rows;
            
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[$(this).index()];
                y = rows[i + 1].getElementsByTagName("TD")[$(this).index()];
                
                if (dir === "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir === "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount === 0 && dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    });
});
