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
                                $leader = '';

                                    foreach ($members as $member) {
                                                $totalDonations += $member["donations"];
                                                        $totalReceived += $member["donationsReceived"];
                                                                if ($member['role'] === 'leader') {
                                                                                $leader = $member['name'];
                                                                }
                                    }

                                        // Store clan name, tag, total donations, total received, logo URL, and link in the array
                                            $clanData[] = [
                                                        'name' => $data["name"],
                                                                'tag' => $data["tag"],
                                                                        'totalDonations' => $totalDonations,
                                                                                'totalReceived' => $totalReceived,
                                                                                        'logo' => $data["badgeUrls"]["medium"], // Using the medium size logo URL
                                                                                                'leader' => $leader, // Store the leader's name
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
        <title>Top Req Clans - Current Season</title> <!-- Updated title -->
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
                                                                padding: 15px;
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
                                                                                                                        .refresh-button {
                                                                                                                                        display: block;
                                                                                                                                                    margin: 20px auto;
                                                                                                                                                                text-align: center;
                                                                                                                        }
                                                                                                                                .clan-info {
                                                                                                                                                display: inline-block;
                                                                                                                                                            text-align: left;
                                                                                                                                                                        vertical-align: middle;
                                                                                                                                }
                                                                                                                                    </style>
                                                                                                                                    </head>
                                                                                                                                    <body>
                                                                                                                                    <div class="container">
                                                                                                                                        <h1>Top Req Clans - Current Season</h1> <!-- Updated header -->

                                                                                                                                            <!-- Refresh button -->
                                                                                                                                                <button class="refresh-button" onclick="location.reload();">Refresh Page</button>

                                                                                                                                                    <div class="table-responsive">
                                                                                                                                                            <table class="table movie-table">
                                                                                                                                                                        <thead>
                                                                                                                                                                                    <tr class="movie-table-head">
                                                                                                                                                                                                    <th>#</th>
                                                                                                                                                                                                                    <th>Clan</th> <!-- Updated column name -->
                                                                                                                                                                                                                                    <th>Leader</th> <!-- New column for the leader -->
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
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a href="<?= $clan['link']; ?>" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <img src="<?= htmlspecialchars($clan['logo']); ?>" alt="Clan Logo" class="clan-logo">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="clan-info">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <a href="<?= $clan['link']; ?>" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?= htmlspecialchars($clan['name']); ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <a href="<?= $clan['link']; ?>" target="_blank">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?= htmlspecialchars($clan['tag']); ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </a> <!-- Clan tag immediately under clan name -->
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td><?= htmlspecialchars($clan['leader']); ?></td> <!-- Display the leader's name -->
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
                                }
                        }
})
                                            ]
                                                                }
                                    }
                }
}