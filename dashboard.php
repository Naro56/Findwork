<?php
require_once 'includes/header.php';

// Redirect if not logged in or if user is a recruiter
if (!isLoggedIn() || isRecruiter()) {
    header('Location: /');
    exit;
}

// Get user's applications
$stmt = $conn->prepare("
    SELECT a.*, j.title as job_title, u.name as company_name
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN users u ON j.company_id = u.id
    WHERE a.user_id = ?
    ORDER BY a.applied_on DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$applications = $stmt->get_result();
?>

<div class="container">
    <h1>My Applications</h1>
    
    <?php if ($applications->num_rows === 0): ?>
        <div class="alert alert-info">
            You haven't applied to any jobs yet. 
            <a href="/">Browse jobs</a> to find opportunities that match your skills.
        </div>
    <?php else: ?>
        <div class="applications-grid">
            <?php while ($app = $applications->fetch_assoc()): ?>
                <div class="application-card">
                    <div class="application-header">
                        <h2 class="job-title"><?php echo htmlspecialchars($app['job_title']); ?></h2>
                        <div class="status-badge status-<?php echo $app['status']; ?>">
                            <?php
                            switch ($app['status']) {
                                case 'selected':
                                    echo '🎉 Selected';
                                    break;
                                case 'rejected':
                                    echo '❌ Rejected';
                                    break;
                                default:
                                    echo '📝 Applied';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="company-name"><?php echo htmlspecialchars($app['company_name']); ?></div>
                    <div class="applied-date">Applied on: <?php echo date('M d, Y', strtotime($app['applied_on'])); ?></div>
                    
                    <?php if ($app['status'] == 'selected'): ?>
                        <div class="selection-message">
                            <p>We are thrilled to have you join our team! Your skills and experience impressed us, and we believe you'll be a valuable addition to our company.</p>
                            <p>Our HR team will contact you shortly with next steps.</p>
                        </div>
                    <?php elseif ($app['status'] == 'rejected'): ?>
                        <div class="rejection-message">
                            <p>Thank you for your interest in this position. While we were impressed with your qualifications, we've decided to move forward with other candidates at this time.</p>
                            <p>Don't give up! Keep applying and improving your skills. The right opportunity is waiting for you.</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="application-details mt-3">
                        <h4>Your Application</h4>
                        <p><strong>Resume:</strong> <a href="/job-portal/<?php echo htmlspecialchars($app['resume_path']); ?>" target="_blank">View Resume</a></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.applications-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

.application-card {
    background: #fff;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.application-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.job-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.company-name {
    color: #4b5563;
    margin-bottom: 0.5rem;
}

.applied-date {
    font-size: 0.875rem;
    color: #6b7280;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-applied {
    background-color: #dbeafe;
    color: #1e40af;
}

.status-selected {
    background-color: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background-color: #fee2e2;
    color: #b91c1c;
}

.cover-letter-text {
    background-color: #f9fafb;
    padding: 1rem;
    border-radius: 4px;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    white-space: pre-line;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-3 {
    margin-top: 0.75rem;
}

.selection-message {
    background-color: #d1fae5;
    border-left: 4px solid #059669;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: 0 4px 4px 0;
}

.selection-message p {
    margin-bottom: 0.5rem;
}

.selection-message p:last-child {
    margin-bottom: 0;
}

.rejection-message {
    background-color: #fee2e2;
    border-left: 4px solid #dc2626;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: 0 4px 4px 0;
}

.rejection-message p {
    margin-bottom: 0.5rem;
}

.rejection-message p:last-child {
    margin-bottom: 0;
}
</style>

<?php require_once 'includes/footer.php'; ?> 
