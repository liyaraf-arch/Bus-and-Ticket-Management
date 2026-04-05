<?php
// pages/event.php - Upcoming Events Page
require_once '../includes/config.php';

$page_title = __('events');

// Get all upcoming events
$query = "SELECT * FROM events WHERE status = 'upcoming' AND event_date >= CURDATE() ORDER BY event_date ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="<?php echo $current_lang == 'bn' ? 'bn' : 'en'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .events-hero {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            padding: 50px 0;
            color: white;
            text-align: center;
        }
        
        .events-hero h1 {
            color: white;
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .events-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .event-card {
            background: white;
            border-radius: 20px;
            margin-bottom: 30px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-wrap: wrap;
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .event-image {
            width: 300px;
            min-height: 250px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .event-image i {
            font-size: 80px;
            color: rgba(255,255,255,0.3);
        }
        
        .event-image .days-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .event-info {
            flex: 1;
            padding: 25px;
        }
        
        .event-title {
            font-size: 24px;
            color: var(--dark-green);
            margin-bottom: 10px;
        }
        
        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 15px 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .event-meta span {
            color: #666;
            font-size: 14px;
        }
        
        .event-meta i {
            color: var(--primary-green);
            margin-right: 5px;
        }
        
        .event-description {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .event-days {
            display: inline-block;
            background: #f8f9fa;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .btn-get-bus {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-get-bus:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
        }
        
        .no-events {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 20px;
        }
        
        .no-events i {
            font-size: 60px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .event-image {
                width: 100%;
                height: 180px;
            }
            .event-card {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="events-hero">
        <div class="container">
            <h1><i class="fas fa-calendar-alt"></i> <?php echo __('Upcoming Events'); ?></h1>
            <p><?php echo __('Events Description'); ?></p>
        </div>
    </div>
    
    <div class="events-container">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($event = mysqli_fetch_assoc($result)): 
                $days_left = ceil((strtotime($event['event_date']) - time()) / 86400);
                $event_date = new DateTime($event['event_date']);
                $today = new DateTime();
                $diff = $today->diff($event_date);
                $days = $diff->days;
            ?>
                <div class="event-card">
                    <!-- pages/event.php - Update event image display -->

<div class="event-image">
    <?php if($event['image_path'] && file_exists('../' . $event['image_path'])): ?>
        <img src="../<?php echo $event['image_path']; ?>" alt="<?php echo $event['title']; ?>" style="width:100%; height:100%; object-fit:cover;">
    <?php else: ?>
        <i class="fas fa-calendar-alt"></i>
    <?php endif; ?>
    <div class="days-badge">
        <?php if($days == 0): ?>
            <?php echo __('today'); ?>
        <?php elseif($days == 1): ?>
            <?php echo __('tomorrow'); ?>
        <?php else: ?>
            <?php echo $days . ' ' . __('Day\'s Left'); ?>
        <?php endif; ?>
    </div>
</div>
                    
                    <div class="event-info">
                        <h3 class="event-title"><?php echo $event['title']; ?></h3>
                        
                        <div class="event-meta">
                            <span><i class="fas fa-calendar"></i> <?php echo date('d M, Y', strtotime($event['event_date'])); ?></span>
                            <?php if($event['event_time']): ?>
                                <span><i class="fas fa-clock"></i> <?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
                            <?php endif; ?>
                            <span><i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?></span>
                            <?php if($event['venue']): ?>
                                <span><i class="fas fa-building"></i> <?php echo $event['venue']; ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <p class="event-description"><?php echo substr($event['description'], 0, 200); ?>...</p>
                        
                        <div class="event-days">
                            <i class="fas fa-hourglass-half"></i> 
                            <?php 
                            if($days == 0) echo __('Starting today');
                            elseif($days == 1) echo __('Starting tomorrow');
                            else echo sprintf(__('Starting in days'), $days);
                            ?>
                        </div>
                        
                        <button class="btn-get-bus" onclick="searchBusForEvent('<?php echo $event['from_city']; ?>', '<?php echo $event['to_city']; ?>')">
                            <i class="fas fa-bus"></i> <?php echo __('Get Bus'); ?>
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-events">
                <i class="fas fa-calendar-times"></i>
                <h3><?php echo __('No Upcoming Events'); ?></h3>
                <p><?php echo __('Check back later'); ?></p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function searchBusForEvent(from, to) {
            const today = new Date().toISOString().split('T')[0];
            window.location.href = `bus.php?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&date=${today}`;
        }
    </script>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>