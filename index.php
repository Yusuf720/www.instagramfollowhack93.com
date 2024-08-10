<?php
// Clash of Clans API anahtarınızı buraya ekleyin
$apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6IjJiOTBkODViLTAxNzAtNDg3OS1iNDVlLWExNDliZjdlZmY1MCIsImlhdCI6MTcyMzEyNzE3Nywic3ViIjoiZGV2ZWxvcGVyL2UzZmZjOTdjLTkzNDItMzQxZi1kY2ZiLWI4YzY5MTU3NTQ3OSIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjMxLjE4Ni4xMS4xMTMiXSwidHlwZSI6ImNsaWVudCJ9XX0.FwxMBOWF3tz9V5SH6ChNfv7JlEzhdtQ3H4xe9DDhwkgfmSzf-h63ammFtGUBzxD2bmVAWF88uYVz8N3i4gEBXw';

// Almak istediğiniz klanların taglerini buraya ekleyin
$clanTags = ['#GPGPCLJJ', '#QLVROLCC', '#P9LJC89Y','#990JORL2','#8P2QG08P','#2Y8G2PU2P','#89R889VG','#2PC2C8YU0','#802ULYR2','#V2Y8YVLC','#LY2RGCYU','#UUQ00JL2','#YJRCGJ9Y','#JVVRLG0','#8U8U2V2L','#2V20YLQU','#PYPC00JC','#VVJCY9J','#2Q9GCVJ8U','#RYPU0G2C','#2PJLJ8Q0G','#P98QRCYL','#L0RJJ0GU','#PCP0CQJG','#PRBC9JRL','#2CVPQRL9','#G0Q80CR9','#C9YVPLL9','#YYCLQYJL','#20UYV9GCR','#29LJ8PG8G','#989J0YY0','#YGUCR8GG','#22CJBUJVY','#29RCG2VGP','#8P0PRC9L','#29L20PJVU','#V0R2L90J','#2QQL0YLGV','#L0UGCJC9','#2Q82GC0VU','#2QYPU299U','#2YGGJJL9C','#2P29G9L9C','#JGYGGUQ2','#Q8ZY029R','#LGV0CCRR','#Q09VJJR8','#2QG2GL9V9'];

// Verilerin önbelleklenmesi için kullanılan fonksiyon
function getClanData($clanTag, $apiKey) {
        $cacheFile = 'cache/' . urlencode($clanTag) . '.json';
            $cacheTime = 3600; // 1 saat önbellekleme süresi

                if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
                            $response = file_get_contents($cacheFile);
                } else {
                            $url = "https://api.clashofclans.com/v1/clans/" . urlencode($clanTag);
                                    $headers = [
                                                    "Authorization: Bearer " . $apiKey,
                                    ];

                                            $ch = curl_init();
                                                    curl_setopt($ch, CURLOPT_URL, $url);
                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                                            $response = curl_exec($ch);
                                                                                    curl_close($ch);

                                                                                            file_put_contents($cacheFile, $response);
                }

                    return json_decode($response, true);
}

$clans = [];
foreach ($clanTags as $clanTag) {
        $clans[] = getClanData($clanTag, $apiKey);
}

// Üyelerin toplam bağışlarını hesaplayan fonksiyon
function calculateTotalDonations($clan) {
        $totalDonations = 0;
            foreach ($clan['memberList'] as $member) {
                        $totalDonations += $member['donations'];
            }
                return $totalDonations;
}

function calculateTotalReceived($clan) {
        return array_sum(array_column($clan['memberList'], 'donationsReceived'));
}

// Klan liderini bulmak için fonksiyon
function getClanLeader($clan) {
        foreach ($clan['memberList'] as $member) {
                    if ($member['role'] === 'leader') {
                                    return $member['name'];
                    }
        }
            return 'Unknown Leader'; // Lider bulunamazsa
}

// Klanları toplam bağışa göre sıralama
usort($clans, function ($a, $b) {
        return calculateTotalDonations($b) - calculateTotalDonations($a);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Top Req Clans - Current Season</title>
                <style>
                        table {
                                        width: 100%;
                                                    border-collapse: collapse;
                        }
                                th, td {
                                                padding: 10px;
                                                            text-align: left;
                                                                        border-bottom: 1px solid #ddd;
                                }
                                        tr:nth-child(even) {
                                                        background-color: #f2f2f2;
                                        }
                                                th {
                                                                background-color: #f5f5dc; /* Krem rengi */
                                                                            color: #333;
                                                                                        font-weight: bold;
                                                }
                                                        .first-row {
                                                                        background-color: #FFD700; /* Koyu sarı */
                                                        }
                                                                .second-row {
                                                                                background-color: #D3D3D3; /* Gri */
                                                                }
                                                                        .third-row {
                                                                                        background-color: #ADD8E6; /* Mavi */
                                                                        }
                                                                                .clan-logo {
                                                                                                width: 50px;
                                                                                                            height: 50px;
                                                                                                                        vertical-align: middle;
                                                                                                                                    margin-right: 10px; /* Logo ile metin arasında boşluk oluşturmak için */
                                                                                }
                                                                                        .clan-info {
                                                                                                        display: flex;
                                                                                                                    align-items: center;
                                                                                        }
                                                                                                .clan-info div {
                                                                                                                margin-left: 5px; /* Logo ve metin arasındaki boşluk azaldı */
                                                                                                }
                                                                                                        .clan-name {
                                                                                                                        font-weight: bold;
                                                                                                        }
                                                                                                                .clan-tag {
                                                                                                                                font-size: 0.9em;
                                                                                                                                            color: #666;
                                                                                                                }
                                                                                                                        .donations, .received {
                                                                                                                                        font-weight: bold;
                                                                                                                        }
                                                                                                                                .leader {
                                                                                                                                                text-align: center; /* Lider sütunundaki metinleri ortalar */
                                                                                                                                }
                                                                                                                                        a {
                                                                                                                                                        text-decoration: none;
                                                                                                                                                                    color: #333;
                                                                                                                                        }
                                                                                                                                                a:hover {
                                                                                                                                                                text-decoration: underline;
                                                                                                                                                }
                                                                                                                                                        @media (max-width: 600px) {
                                                                                                                                                                        th, td {
                                                                                                                                                                                            padding: 5px;
                                                                                                                                                                                                            font-size: 12px;
                                                                                                                                                                        }
                                                                                                                                                                                    .clan-logo {
                                                                                                                                                                                                        width: 30px;
                                                                                                                                                                                                                        height: 30px;
                                                                                                                                                                                    }
                                                                                                                                                                                                .clan-info div {
                                                                                                                                                                                                                    margin-left: 5px;
                                                                                                                                                                                                }
                                                                                                                                                                                                            .donations, .received {
                                                                                                                                                                                                                                font-size: 12px;
                                                                                                                                                                                                            }
                                                                                                                                                        }
                                                                                                                                                            </style>
                                                                                                                                                            </head>
                                                                                                                                                            <body>
                                                                                                                                                                <h1>Top Req Clans - Current Season</h1>
                                                                                                                                                                    <table>
                                                                                                                                                                            <thead>
                                                                                                                                                                                        <tr>
                                                                                                                                                                                                        <th>#</th>
                                                                                                                                                                                                                        <th>Clan</th>
                                                                                                                                                                                                                                        <th class="leader">Leader</th>
                                                                                                                                                                                                                                                        <th class="donations">Total Donations</th>
                                                                                                                                                                                                                                                                        <th class="received">Total Received</th>
                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                            </thead>
                                                                                                                                                                                                                                                                                                    <tbody>
                                                                                                                                                                                                                                                                                                                <?php foreach ($clans as $index => $clan): ?>
                                                                                                                                                                                                                                                                                                                                <?php 
                                                                                                                                                                                                                                                                                                                                                    $totalDonations = calculateTotalDonations($clan); 
                                                                                                                                                                                                                                                                                                                                                                        $totalReceived = calculateTotalReceived($clan);
                                                                                                                                                                                                                                                                                                                                                                                            $leaderName = getClanLeader($clan);
                                                                                                                                                                                                                                                                                                                                                                                                                $rowClass = '';
                                                                                                                                                                                                                                                                                                                                                                                                                                    if ($index == 0) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                $rowClass = 'first-row';
                                                                                                                                                                                                                                                                                                                                                                                                                                    } elseif ($index == 1) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                $rowClass = 'second-row';
                                                                                                                                                                                                                                                                                                                                                                                                                                    } elseif ($index == 2) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                $rowClass = 'third-row';
                                                                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                                                                                                    ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <tr class="<?= $rowClass ?>">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td><?= $index + 1 ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <a href="clan.php?tag=<?= urlencode($clan['tag'])
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ?>" class="clan-info">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <img src="<?= $clan['badgeUrls']['medium'] ?>" alt="Clan Logo" class="clan-logo">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <span class="clan-name"><?= $clan['name'] ?></span><br>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <span class="clan-tag"><?= $clan['tag'] ?></span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </a>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <td class="leader"><?= $leaderName ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <td class="donations"><?= number_format($totalDonations) ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <td class="received"><?= number_format($totalReceived) ?></td>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </tr>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php endforeach; ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </tbody>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </table>
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
                    }
        }
}
}
            }
}
}
                                    ]
                }
                }
}