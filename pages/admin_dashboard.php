<?php
session_start();
include '../includes/header.php';
include '../includes/config.php';

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Admins only.";
    exit;
}

// Pagination and records per page settings
$results_per_page = isset($_GET['results_per_page']) ? $_GET['results_per_page'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Sorting parameters
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Get all tables dynamically from the database
$tables_query = "SHOW TABLES FROM ecommerce_system";
$tables_result = mysqli_query($conn, $tables_query);
$tables = [];
while ($table_row = mysqli_fetch_row($tables_result)) {
    $tables[] = $table_row[0]; // Push table name to the array
}

$table = isset($_GET['table']) && in_array($_GET['table'], $tables) ? $_GET['table'] : $tables[0]; // Default to the first table

// Query to get data from the selected table
$query = "SELECT * FROM $table ORDER BY $sort_column $sort_order LIMIT $start_from, $results_per_page";
$result = mysqli_query($conn, $query);

// Get the total number of rows for pagination
$total_query = "SELECT COUNT(id) FROM $table";
$total_result = mysqli_query($conn, $total_query);
$total_rows = mysqli_fetch_array($total_result)[0];
$total_pages = ceil($total_rows / $results_per_page);
?>

<!-- Add Bootstrap CSS (via CDN) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJwT4QSseDgeD5dEGRgpm1fSw7tM6fs4Zl7C9RwsT5iFzB7LRgFmPquxFYdP" crossorigin="anonymous">

<!-- Custom CSS -->
<style>
    body {
        background-color: #f4f4f9;
    }
    .container {
        margin-top: 50px;
    }
    table {
        width: 100%;
        margin-top: 20px;
    }
    th, td {
        text-align: center;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .card-header {
        background-color: #007bff;
        color: white;
    }
    img {
        max-width: 100px;
        height: auto;
    }
</style>

<div class="container">
    <h2 class="text-center">Admin Dashboard</h2>

    <!-- Table Selector (Dynamic Tables) -->
    <div class="mb-3">
        <label for="tableSelect" class="form-label">Select Table:</label>
        <select id="tableSelect" class="form-select" onchange="changeTable()">
            <?php foreach ($tables as $table_name) { ?>
                <option value="<?php echo $table_name; ?>" <?php if ($table == $table_name) echo 'selected'; ?>>
                    <?php echo ucfirst($table_name); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Records Per Page -->
    <div class="mb-3">
        <label for="recordsPerPage" class="form-label">Records Per Page:</label>
        <select id="recordsPerPage" class="form-select" onchange="changeRecordsPerPage()">
            <option value="10" <?php if ($results_per_page == 10) echo 'selected'; ?>>10</option>
            <option value="25" <?php if ($results_per_page == 25) echo 'selected'; ?>>25</option>
            <option value="50" <?php if ($results_per_page == 50) echo 'selected'; ?>>50</option>
        </select>
    </div>

    <!-- Table inside a Card for better presentation -->
    <div class="card">
        <div class="card-header">
            <h4><?php echo ucfirst($table); ?> Management</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        <?php
                        // Fetch the columns from the selected table dynamically
                        $columns_query = "DESCRIBE $table";
                        $columns_result = mysqli_query($conn, $columns_query);
                        while ($column = mysqli_fetch_assoc($columns_result)) {
                            // Hide password and user type/role columns
                            if ($column['Field'] != 'password' && $column['Field'] != 'user_type') {
                                echo "<th>" . ucfirst($column['Field']) . "</th>";
                            }
                        }
                        ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr id="row_<?php echo $row['id']; ?>">
                            <?php
                                // Display each field in the row dynamically (excluding password and role)
                                foreach ($row as $key => $value) {
                                    if ($key != 'password' && $key != 'user_type') { // Don't display password and role
                                        // Check if the value is an image path
                                        if (strpos($value, 'jpg') !== false || strpos($value, 'jpeg') !== false || strpos($value, 'png') !== false || strpos($value, 'gif') ||strpos($value, 'webp')  !== false) {
                                            echo "<td><img src='../uploads/".htmlspecialchars($value)."' alt='Image' onclick='updateImage($row[id])' style='cursor:pointer;'></td>";
                                        } else {
                                            echo "<td>" . htmlspecialchars($value) . "</td>";
                                        }
                                    }
                                }
                            ?>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="viewData(<?php echo $row['id']; ?>)">View</button>
                                <button class="btn btn-warning btn-sm" onclick="editData(<?php echo $row['id']; ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteData(<?php echo $row['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&results_per_page=<?php echo $results_per_page; ?>&table=<?php echo $table; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal for View -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">View Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="viewContent">Loading...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" enctype="multipart/form-data">
                    <!-- Dynamic Fields will be populated via JS -->
                    <div id="editContent">Loading...</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS (via CDN) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Handle table change via the dropdown
    function changeTable() {
        const table = document.getElementById('tableSelect').value;
        window.location.href = '?table=' + table + '&results_per_page=' + '<?php echo $results_per_page; ?>' + '&page=1';
    }

    // Handle records per page change
    function changeRecordsPerPage() {
        const results_per_page = document.getElementById('recordsPerPage').value;
        window.location.href = '?results_per_page=' + results_per_page + '&table=' + '<?php echo $table; ?>' + '&page=1';
    }

    // View Data Modal
    function viewData(id) {
        fetch(`view_item.php?id=${id}&table=<?php echo $table; ?>`)
            .then(response => response.json())
            .then(data => {
                let viewContent = '';
                for (const key in data) {
                    viewContent += `<p><strong>${key}</strong>: ${data[key]}</p>`;
                }
                document.getElementById('viewContent').innerHTML = viewContent;
                new bootstrap.Modal(document.getElementById('viewModal')).show();
            });
    }

    // Edit Data Modal
    function editData(id) {
        const table = '<?php echo $table; ?>';
        fetch(`edit_item.php?id=${id}&table=${table}`)
            .then(response => response.json())
            .then(data => {
                let formContent = '';
                for (const key in data) {
                    if (key === 'password' || key === 'user_type') continue;
                    if (key === 'role') {
                        formContent += `<div class="mb-3">
                                            <label for="${key}" class="form-label">Role</label>
                                            <select class="form-select" id="${key}" name="${key}">
                                                <option value="admin" ${data[key] === 'admin' ? 'selected' : ''}>Admin</option>
                                                <option value="vendor" ${data[key] === 'vendor' ? 'selected' : ''}>Vendor</option>
                                                <option value="customer" ${data[key] === 'customer' ? 'selected' : ''}>Customer</option>
                                            </select>
                                        </div>`;
                    } else if (key === 'status' || key === 'vendor_status') {
                        formContent += `<div class="mb-3">
                                            <label for="${key}" class="form-label">Status</label>
                                            <select class="form-select" id="${key}" name="${key}">
                                                <option value="pending" ${data[key] === 'pending' ? 'selected' : ''}>Pending</option>
                                                <option value="published" ${data[key] === 'published' ? 'selected' : ''}>Published</option>
                                            </select>
                                        </div>`;
                    } else if (key === 'image') {
                        formContent += `<div class="mb-3">
                                            <label for="${key}" class="form-label">Image</label>
                                            <div>
                                                <img src="../uploads/${data[key]}" alt="Current Image" style="max-width: 100px; height: auto;">
                                            </div>
                                            <input type="file" class="form-control" id="${key}" name="${key}">
                                        </div>`;
                    } else {
                        formContent += `<div class="mb-3">
                                            <label for="${key}" class="form-label">${key}</label>
                                            <input type="text" class="form-control" id="${key}" name="${key}" value="${data[key]}">
                                        </div>`;
                    }
                }
                document.getElementById('editContent').innerHTML = formContent;

                // Ensure the form submission is handled by the updateData function
                document.getElementById('saveChangesBtn').onclick = function () {
                    updateData(id);
                };

                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
    }

    // Save Changes in Edit Data Modal
   // JavaScript code for submitting the form
function updateData(id) {
    const formData = new FormData(document.getElementById('editForm'));
    formData.append('id', id); // Add ID of the record to be updated
    formData.append('table', `<?php echo $table; ?>`); // Add table name dynamically

    fetch('update_item.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Data updated successfully');
            // Optionally update the page content or reload the page
            window.location.reload(); // Refresh the page to reflect changes
        } else {
            alert('Failed to update data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}

// Delete Data Modal
function deleteData(id) {
    // Show the delete confirmation modal
    new bootstrap.Modal(document.getElementById('deleteModal')).show();

    // Set the delete action to the confirm button
    document.getElementById('confirmDeleteBtn').onclick = function() {
        // Call the delete function
        deleteRecord(id);
    };
}

// Function to handle record deletion
function deleteRecord(id) {
    fetch(`delete_item.php?id=${id}&table=<?php echo $table; ?>`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Record deleted successfully');
            // Remove the row from the table
            document.getElementById('row_' + id).remove();
        } else {
            alert('Failed to delete record: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}

</script>
</body>
</html>
