<?php
// TimeLogGuide.php
$showGuide = $_GET['show_guide'] ?? false;
$currentStepIndex = intval($_GET['step'] ?? 0);

$guideSteps = [
    [
        'title' => "Time Log Table Overview",
        'content' => "Welcome to the Time Log Table! This page provides a comprehensive view of employee work hours, overtime, and performance indicators.",
        'highlight' => ['top' => '0', 'left' => '0', 'width' => '100%', 'height' => '100%'],
        'position' => ['top' => '50%', 'left' => '50%', 'transform' => 'translate(-50%, -50%)']
    ],
    [
        'title' => "Understanding Indicators",
        'content' => "Green: Normal hours (45h), Yellow: Some overtime (42h + 3h OT), Red: Hours owed (40h + 5h owed) - monitor closely.",
        'highlight' => ['top' => '120px', 'left' => '1rem', 'width' => 'calc(100% - 2rem)', 'height' => '200px'],
        'position' => ['top' => '350px', 'left' => '50%', 'transform' => 'translateX(-50%)']
    ]
];

$currentStep = $guideSteps[$currentStepIndex] ?? $guideSteps[0];
?>

<?php if ($showGuide): ?>
<div class="time-log-guide-overlay">
    <!-- Highlight Overlay -->
    <div class="highlight-overlay" style="
        top: <?php echo $currentStep['highlight']['top']; ?>;
        left: <?php echo $currentStep['highlight']['left']; ?>;
        width: <?php echo $currentStep['highlight']['width']; ?>;
        height: <?php echo $currentStep['highlight']['height']; ?>;
    "></div>
    
    <!-- Guide Content -->
    <div class="guide-content" style="
        top: <?php echo $currentStep['position']['top']; ?>;
        left: <?php echo $currentStep['position']['left']; ?>;
        transform: <?php echo $currentStep['position']['transform']; ?>;
    ">
        <div class="guide-header">
            <h3><?php echo $currentStep['title']; ?></h3>
            <button onclick="closeGuide()" class="close-btn">&times;</button>
        </div>
        <div class="guide-body">
            <p><?php echo $currentStep['content']; ?></p>
            <div class="step-indicator">
                Step <?php echo $currentStepIndex + 1; ?> of <?php echo count($guideSteps); ?>
            </div>
        </div>
        <div class="guide-footer">
            <?php if ($currentStepIndex > 0): ?>
                <a href="?show_guide=1&step=<?php echo $currentStepIndex - 1; ?>" class="btn-secondary">Back</a>
            <?php endif; ?>
            <?php if ($currentStepIndex < count($guideSteps) - 1): ?>
                <a href="?show_guide=1&step=<?php echo $currentStepIndex + 1; ?>" class="btn-primary">Next</a>
            <?php else: ?>
                <a href="?show_guide=0" class="btn-primary">Finish</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function closeGuide() {
    window.location.href = '?show_guide=0';
}
</script>

<style>
.time-log-guide-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    pointer-events: none;
}

.highlight-overlay {
    position: absolute;
    border: 3px solid #10b981;
    border-radius: 12px;
    box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.7);
    pointer-events: none;
    transition: all 0.4s ease;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { border-color: #10b981; }
    50% { border-color: #34d399; }
    100% { border-color: #10b981; }
}

.guide-content {
    position: absolute;
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    min-width: 350px;
    max-width: 450px;
    z-index: 10001;
    pointer-events: all;
    border: 1px solid #e2e8f0;
}

.guide-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px 0 25px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 15px;
}

.guide-header h3 {
    margin: 0;
    color: #065f46;
    font-size: 1.3em;
    font-weight: 600;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    color: #718096;
    padding: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-btn:hover {
    background: #f0fdf4;
}

.guide-body {
    padding: 20px 25px;
}

.guide-body p {
    margin: 0 0 15px 0;
    line-height: 1.6;
    color: #374151;
    font-size: 1em;
}

.step-indicator {
    font-size: 0.9em;
    color: #059669;
    font-weight: 500;
    text-align: center;
    padding: 8px 0;
    border-top: 1px solid #e2e8f0;
}

.guide-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 25px;
    border-top: 1px solid #e2e8f0;
}

.btn-primary {
    background: #10b981;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #059669;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #d1fae5;
    color: #065f46;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #a7f3d0;
    color: #064e3b;
}

@media (max-width: 768px) {
    .guide-content {
        min-width: 300px;
        max-width: 350px;
        margin: 0 20px;
    }
    
    .guide-header,
    .guide-body,
    .guide-footer {
        padding: 15px 20px;
    }
}
</style>
<?php endif; ?>