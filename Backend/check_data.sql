-- Check what data exists in record_backups table

-- 1. Check total records
SELECT COUNT(*) as total_records FROM record_backups;

-- 2. Check date range of existing records
SELECT 
    MIN(date) as earliest_date, 
    MAX(date) as latest_date 
FROM record_backups;

-- 3. Check records for the specific week (2025-10-27)
SELECT * FROM record_backups 
WHERE date >= '2025-10-27' AND date < '2025-11-03'
LIMIT 10;

-- 4. Check what weeks have data
SELECT 
    YEARWEEK(date, 1) AS week_number,
    MIN(date) AS week_start,
    MAX(date) AS week_end,
    COUNT(*) as record_count
FROM record_backups
GROUP BY YEARWEEK(date, 1)
ORDER BY week_number DESC
LIMIT 10;

-- 5. Check if clockin/clockout times are NULL
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN clockin_time IS NULL THEN 1 ELSE 0 END) as null_clockin,
    SUM(CASE WHEN clockout_time IS NULL THEN 1 ELSE 0 END) as null_clockout,
    SUM(CASE WHEN type = 'Work' THEN 1 ELSE 0 END) as work_records,
    SUM(CASE WHEN status IN ('Early', 'OnTime', 'Late') THEN 1 ELSE 0 END) as valid_status
FROM record_backups;
