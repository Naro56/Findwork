<?php
require_once 'includes/header.php';

// Get filter parameters
$type = isset($_GET['type']) ? $_GET['type'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$experience = isset($_GET['experience']) ? $_GET['experience'] : '';
$salary = isset($_GET['salary']) ? $_GET['salary'] : '';

// Build query
$query = "SELECT j.*, u.name as company_name 
          FROM jobs j 
          JOIN users u ON j.company_id = u.id 
          WHERE j.is_closed = 0";

$params = [];
$types = "";

if ($type) {
    $query .= " AND j.type = ?";
    $params[] = $type;
    $types .= "s";
}

if ($location) {
    $query .= " AND j.location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}

if ($experience) {
    $query .= " AND j.experience_required LIKE ?";
    $params[] = "%$experience%";
    $types .= "s";
}

if ($salary) {
    $query .= " AND j.salary LIKE ?";
    $params[] = "%$salary%";
    $types .= "s";
}

$query .= " ORDER BY j.created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h1 class="page-title">Find Your Dream Job</h1>
    
    <!-- Filters -->
    <div class="filters">
        <form method="GET" action="index.php">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="type">Job Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="full-time" <?php echo $type === 'full-time' ? 'selected' : ''; ?>>Full Time</option>
                        <option value="internship" <?php echo $type === 'internship' ? 'selected' : ''; ?>>Internship</option>
                        <option value="part-time" <?php echo $type === 'part-time' ? 'selected' : ''; ?>>Part Time</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" id="location" class="form-control" 
                           value="<?php echo htmlspecialchars($location); ?>" placeholder="Enter location">
                </div>
                
                <div class="filter-group">
                    <label for="experience">Experience</label>
                    <input type="text" name="experience" id="experience" class="form-control" 
                           value="<?php echo htmlspecialchars($experience); ?>" placeholder="e.g., 2-3 years">
                </div>
                
                <div class="filter-group">
                    <label for="salary">Salary</label>
                    <input type="text" name="salary" id="salary" class="form-control" 
                           value="<?php echo htmlspecialchars($salary); ?>" placeholder="e.g., 10L">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Apply Filters</button>
            <a href="index.php" class="btn btn-secondary">Clear Filters</a>
        </form>
    </div>

    <!-- Job Listings -->
    <div class="job-grid">
        <?php if ($result->num_rows === 0): ?>
            <div class="no-jobs-message">
                <p>No jobs found matching your criteria. Try adjusting your filters.</p>
            </div>
        <?php else: ?>
            <?php while ($job = $result->fetch_assoc()): ?>
                <div class="job-card">
                    <h2 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h2>
                    <div class="company-name"><?php echo htmlspecialchars($job['company_name']); ?></div>
                    
                    <div class="job-details">
                        <span><i class="location-icon">📍</i> <?php echo htmlspecialchars($job['location']); ?></span>
                        <span><i class="salary-icon">💰</i> <?php echo htmlspecialchars($job['salary']); ?></span>
                        <span><i class="experience-icon">⏳</i> <?php echo htmlspecialchars($job['experience_required']); ?></span>
                        <span><i class="type-icon">📝</i> <?php echo htmlspecialchars($job['type']); ?></span>
                    </div>
                    
                    <?php if (isLoggedIn() && !isRecruiter()): ?>
                        <a href="apply.php?job_id=<?php echo $job['id']; ?>" class="btn btn-primary">Apply Now</a>
                    <?php elseif (isLoggedIn() && isRecruiter()): ?>
                        <div class="recruiter-notice">You're logged in as a recruiter</div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login to Apply</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 
