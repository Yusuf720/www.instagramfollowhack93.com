<?php
require_once("yadro.php");

// Assume you have an array of clan tags
$clanTags = ['#GPGPCLJJ', '#QLVR0LCC']; // Add your clan tags here
$clanData = [];

foreach ($clanTags as $clantag) {
        $clash = new ClashofClans();
            $data = $clash->clans($clantag);

                if (isset($data["reason"])) {
                            continue;
                }

                    $members = $data["memberList"];

                        $totalDonations = 0;
                            $totalReceived = 0;

                                foreach ($members as $member) {
                                            $totalDonations += $member["donations"];
                                                    $totalReceived += $member["donationsReceived"];
                                }

                                    // Store clan name, tag, total donations, total received, logo URL, and link in the array
                                        $clanData[] = [
                                                    'name' => $data["name"],
                                                            'tag' => $data["tag"],
                                                                    'totalDonations' => $totalDonations,
                                                                            'totalReceived' => $totalReceived,
                                                                                    'logo' => $data["badgeUrls"]["medium"], // Using the medium size logo URL
                                                                                            'link' => "clan-details.php?tag=" . urlencode($data["tag"]) // Adjusted link to point to the detailed clan page
                                        ];
}

// Sort the clans by total donations in descending order
usort($clanData, function($a, $b) {
        return $b['totalDonations'] <=> $a['totalDonations'];
});
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title>Clan Donation Summary</title>
            <link href="//uzhackersw.uz/theme/theme/css/bootstrap.css" rel="stylesheet" id="bootstrap-css">
                <style>
                        body {
                                        margin: 0;
                                                    padding: 0;
                                                                font-family: Arial, sans-serif;
                        }
                                .container {
                                                width: 100%;
                                                            max-width: 100%;
                                                                        padding: 0;
                                                                                    margin: 0;
                                }
                                        .movie-table {
                                                        width: 100%;
                                                                    margin: 0 auto;
                                                                                border-collapse: collapse;
                                        }
                                                .movie-table th, .movie-table td {
                                                                padding: 15px; /* Adjust the padding to control the row height */
                                                                            text-align: center;
                                                                                        border: 1px solid #ddd;
                                                }
                                                        .dark-row {
                                                                        background: #AEC5E8;
                                                        }
                                                                .light-row {
                                                                                background: #F1F3F0;
                                                                }
                                                                        .movie-table-head {
                                                                                        background: #f0f0f0;
                                                                        }
                                                                                h1 {
                                                                                                text-align: center;
                                                                                                            margin: 15px;
                                                                                                                        font-size: 24px;
                                                                                }
                                                                                        .clan-logo {
                                                                                                        width: 40px;
                                                                                                                    height: 40px;
                                                                                                                                vertical-align: middle;
                                                                                                                                            margin-right: 10px;
                                                                                        }
                                                                                                .first-row {
                                                                                                                background: #FFD700; /* Gold color */
                                                                                                }
                                                                                                        .second-row {
                                                                                                                        background: #ADD8E6; /* Light Blue color */
                                                                                                        }
                                                                                                                .third-row {
                                                                                                                                background: #D3D3D3; /* Light Gray color */
                                                                                                                }
                                                                                                                    </style>
                                                                                                                    </head>
                                                                                                                    <body>
                                                                                                                    <div class="container">
                                                                                                                        <h1>Clan Donation Summary</h1>

                                                                                                                            <div class="table-responsive">
                                                                                                                                    <table class="table movie-table">
                                                                                                                                                <thead>
                                                                                                                                                            <tr class="movie-table-head">
                                                                                                                                                                            <th>#</th>
                                                                                                                                                                                            <th>Clan Name</th>
                                                                                                                                                                                                            <th>Clan Tag</th>
                                                                                                                                                                                                                            <th>Total Donations</th>
                                                                                                                                                                                                                                            <th>Total Received</th> <!-- New column for total received -->
                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                    </thead>
                                                                                                                                                                                                                                                                                <tbody>
                                                                                                                                                                                                                                                                                            <?php foreach ($clanData as $index => $clan): ?>
                                                                                                                                                                                                                                                                                                            <tr class="<?php
                                                                                                                                                                                                                                                                                                                                if ($index == 0) echo 'first-row';
                                                                                                                                                                                                                                                                                                                                                    elseif ($index == 1) echo 'second-row';
                                                                                                                                                                                                                                                                                                                                                                        elseif ($index == 2) echo 'third-row';
                                                                                                                                                                                                                                                                                                                                                                                            else echo $index % 2 == 0 ? 'light-row' : 'dark-row';
                                                                                                                                                                                                                                                                                                                                                                                                            ?>">
                                                                                                                                                                                                                                                                                                                                                                                                                                <td><?= $index + 1; ?></td> <!-- Ranking number -->
                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <!-- Create a clickable link for the clan name with the logo -->
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a href="<?= $clan['link']; ?>" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <img src="<?= htmlspecialchars($clan['logo']); ?>" alt="Clan Logo" class="clan-logo">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?= htmlspecialchars($clan['name']); ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td><?= htmlspecialchars($clan['tag']); ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td><?= number_format($clan['totalDonations']); ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <td><?= number_format($clan['totalReceived']); ?></td> <!-- Display total received -->
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php endforeach; ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </tbody>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </table>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </body>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </html>
                                                                                                                }
                                                                                                        }
                                                                                                }
                                                                                        }
                                                                                }
                                                                        }
                                                                }
                                                        }
                                                }
                                        }
                                }
                        }
})
                                        ]
                                }
                }
}