<?php
include('config/connect.php');

$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_query = "SELECT COUNT(*) as total FROM scholarships WHERE deadline >= CURDATE()";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total'];
$total_pages = ceil($total / $limit);

$query = "SELECT * FROM scholarships WHERE deadline >= CURDATE() ORDER BY deadline ASC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

$output = '';
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>
              <p class="card-text">Apply before <strong>' . date('F d, Y', strtotime($row['deadline'])) . '</strong></p>
              <a href="login.php" class="btn btn-apply">Apply Now</a>
            </div>
          </div>
        </div>';
    }
} else {
    $output = '<p class="text-center">No scholarships are currently available. Please check back later.</p>';
}

$pagination = '<nav><ul class="pagination">';
for ($i = 1; $i <= $total_pages; $i++) {
    $active = ($i == $page) ? ' active' : '';
    $pagination .= '<li class="page-item' . $active . '"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
}
$pagination .= '</ul></nav>';

echo $output . "<!--SPLIT-->" . $pagination;
?>
