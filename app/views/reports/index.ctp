<section id="content">
    
    <div class="wrapper">
    <!-- Main Section -->

        <section class="grid_6 first">
            <div class="columns leading">
                <div class="grid_3 first">
                    <h3>Παίζουν τώρα</h3>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <?php
                            if($recentGames != null){
                                foreach($recentGames as $r){
                                
                                    ?>
                                    <tr>
                                        <td><?php echo $r['name']; ?></td>
                                        <td class="ar"><?php echo $r['category']; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td>Κανένα παιχνίδι δεν παίζεται τώρα :(</td>        
                                    <td class="ar">&nbsp;</td>
                                </tr>    
                                <?php
                            }
                            ?>
                            
                        </tbody>

                    </table>

                </div>

                <div class="grid_3">

                    <h3>-</h3>
                    <hr/>
                    <table class="no-style full">

                        <tbody>
                            <tr>
                                <td>-</td>        
                                <td class="ar">0</td>
                            </tr>
                            <tr>
                                <td>-</td>                        
                                <td class="ar">0</td>
                            </tr>
                            <tr>
                                <td>-</td>                       
                                <td class="ar">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="columns leading">
                <div class="grid_3 first">
                    <h3>Σήμερα (<?php date_default_timezone_set('Europe/Athens'); echo date("d/m/Y");?>)</h3>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <tr>
                                <td>Νέοι χρήστες</td>
                                <td class="ar"><?php echo $todayPlayers; ?></td>
                            </tr>
                            <tr>
                                <td>Παιχνίδια</td>
                                <td class="ar">
                                <?php 
                                $gamesToday = $todayGames['games'];
                                $playersUniqueToday = $todayGames['players'];
                                echo "$gamesToday ($playersUniqueToday)";
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Push notifications</td>
                                <td class="ar"><?php echo $todayPush; ?></td>
                            </tr>
                        </tbody>

                    </table>

                </div>

                <div class="grid_3">

                    <h3>Σύνολο</h3>
                    <hr/>
                    <table class="no-style full">

                        <tbody>
                            <tr>
                                <td>Χρήστες</td>        
                                <td class="ar"><?php echo $totalPlayers; ?></td>
                            </tr>
                            <tr>
                                <td>Παιχνίδια</td>                        
                                <td class="ar">
                                <?php 
                                $gamesTotal = $totalGames['games'];
                                $playersUniqueTotal = $totalGames['players'];
                                echo "$gamesTotal ($playersUniqueTotal)";
                                ?>    
                                </td>
                            </tr>
                            <tr>
                                <td>Push notifications</td>                       
                                <td class="ar"><?php echo $totalPush; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="columns leading">
                <div class="grid_3 first">
                    <h3>Κατηγορίες παιχνιδιών (σήμερα)</h3>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <?php
                            foreach($todayGamesBreakdown as $t){
                                ?>
                                <tr>
                                    <td><?php echo $t['category']; ?></td>
                                    <td class="ar"><?php echo $t['count']; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>

                    </table>

                </div>

                <div class="grid_3">

                    <h3>Κατηγορίες παιχνιδιών (σύνολο)</h3>
                    <hr/>
                    <table class="no-style full">

                        <tbody>
                            <?php
                            foreach($totalGamesBreakdown as $t){
                                ?>
                                <tr>
                                    <td><?php echo $t['category']; ?></td>
                                    <td class="ar"><?php echo $t['count']; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="columns leading">

                <div class="grid_3 first">
                    <h4>Χρήση Facebook</h4>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <tr>
                                <td>Με</td>
                                <td style="width:70%"><div id="progress1" class="progress progress-geografia"><span style="width: <?php echo $facebook['with']; ?>"><b><?php echo $facebook['with']; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><?php echo $facebook['with']; ?></td>
                            </tr>
                            <tr>
                                <td>Χωρίς</td>
                                <td><div class="progress progress-epistimi"><span style="width: <?php echo $facebook['without']; ?>;"><b><?php echo $facebook['without']; ?></b></span></div></td>
                                <td class="ar"><?php echo $facebook['without']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                        
            
                
              </div>

            <div class="columns leading">
                <div class="grid_3 first">
                    <h3>Μέσοι όροι</h3>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <tr>
                                <td>Μέσος όρος παρτίδων (free)</td>
                                <td class="ar"><?php echo $averageGames['a']; ?></td>
                            </tr>
                            <tr>
                                <td>Μέσος όρος παρτίδων (paid)</td>
                                <td class="ar"><?php echo $averageGames['b']; ?></td>
                            </tr>
                        </tbody>

                    </table>

                </div>

                <div class="grid_3">
                    <h4>Push notifications</h4>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <tr>
                                <td>Ναι</td>
                                <td style="width:70%"><div id="progress1" class="progress progress-epistimi"><span style="width: <?php echo $push['yes']; ?>"><b><?php echo $push['yes']; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><?php echo $push['yes']; ?></td>
                            </tr>
                            <tr>
                                <td>Όχι</td>
                                <td><div class="progress progress-athlitika"><span style="width: <?php echo $push['no']; ?>;"><b><?php echo $push['no']; ?></b></span></div></td>
                                <td class="ar"><?php echo $push['no']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>

            <div class="clear">&nbsp;</div>

        </section>

        <!-- Main Section End -->

         <?php
         echo $this->element('menu_report');
         ?>

        <div class="clear"></div>

    </div>
    <div id="push"></div>
</section>