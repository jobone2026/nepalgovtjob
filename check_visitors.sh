#!/bin/bash
echo "====================================="
echo "  JobOne.in Visitor Statistics"
echo "====================================="
echo ""

# Yesterday = access.log.1
echo "=== YESTERDAY (April 21, 2026) ==="
echo -n "Unique IPs (non-bot): "
cat /var/log/nginx/access.log.1 | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | awk '{print $1}' | sort -u | wc -l
echo -n "Total page requests (non-bot): "
cat /var/log/nginx/access.log.1 | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | wc -l
echo ""

# Today = access.log
echo "=== TODAY (April 22, 2026 so far) ==="
echo -n "Unique IPs (non-bot): "
cat /var/log/nginx/access.log | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | awk '{print $1}' | sort -u | wc -l
echo -n "Total page requests (non-bot): "
cat /var/log/nginx/access.log | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | wc -l
echo ""

# Last 7 days from gzipped logs
echo "=== LAST 7 DAYS ==="
for i in 7 6 5 4 3 2; do
    logfile="/var/log/nginx/access.log.${i}.gz"
    if [ -f "$logfile" ]; then
        ips=$(zcat "$logfile" | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | awk '{print $1}' | sort -u | wc -l)
        reqs=$(zcat "$logfile" | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | wc -l)
        date_sample=$(zcat "$logfile" | head -1 | grep -oP '\d{2}/\w{3}/\d{4}')
        echo "  $date_sample: $ips unique IPs, $reqs requests (log.$i.gz)"
    fi
done

# access.log.1 = yesterday
ips=$(cat /var/log/nginx/access.log.1 | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | awk '{print $1}' | sort -u | wc -l)
reqs=$(cat /var/log/nginx/access.log.1 | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | wc -l)
date_sample=$(head -1 /var/log/nginx/access.log.1 | grep -oP '\d{2}/\w{3}/\d{4}')
echo "  $date_sample: $ips unique IPs, $reqs requests (log.1 = yesterday)"

# access.log = today
ips=$(cat /var/log/nginx/access.log | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | awk '{print $1}' | sort -u | wc -l)
reqs=$(cat /var/log/nginx/access.log | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | wc -l)
date_sample=$(head -1 /var/log/nginx/access.log | grep -oP '\d{2}/\w{3}/\d{4}')
echo "  $date_sample: $ips unique IPs, $reqs requests (log = today)"

echo ""
echo "=== YESTERDAY TOP 15 PAGES ==="
cat /var/log/nginx/access.log.1 | grep -vi 'bot\|spider\|crawler\|slurp\|semrush\|ahrefs\|mj12\|dotbot\|blexbot\|bytespider\|yandex\|baidu' | awk '{print $7}' | grep -v '\.\(css\|js\|png\|jpg\|ico\|woff\|svg\|gif\)' | sort | uniq -c | sort -rn | head -15
