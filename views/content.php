<div id="form">
    <?php echo renderPartial('form', array('config' => $config)); ?>
</div>
<div id="result">
    <?php if ($report): ?>
    <?php foreach ($report['plugins'] as $version => $plugins): ?>
    <h1>Netbeans v<?php echo $version; ?></h1>

    <div class="box">
        <h2><?php echo count($plugins['downloaded']); ?> nouveaux plugins</h2>
        <div id="<?php echo "downloaded"; ?>">
            <table>
                <tr>
                    <th>Nom de code</th>
                    <th>Nom</th>
                    <th>Date de sortie</th>
                    <th>Taille</th>
                </tr>
                <?php foreach ($plugins['downloaded'] as $plugin): ?>
                <tr>
                    <td><?php echo $plugin['codename']; ?></td>
                    <td><?php echo $plugin['name']; ?></td>
                    <td><?php echo $plugin['releaseDate']; ?></td>
                    <td class="right"><?php echo $plugin['size']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <h2><?php echo count($plugins['not_downloaded']); ?> plugins existants</h2>
    <table>
        <tr>
            <th>Nom de code</th>
            <th>Nom</th>
            <th>Date de sortie</th>
            <th>Taille</th>
        </tr>
        <?php foreach ($plugins['not_downloaded'] as $plugin): ?>
        <tr>
            <td><?php echo $plugin['codename']; ?></td>
            <td><?php echo $plugin['name']; ?></td>
            <td><?php echo $plugin['releaseDate']; ?></td>
            <td class="right"><?php echo formatBytes($plugin['size']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2><?php echo count($plugins['removed']); ?> plugins archivés</h2>
    <table>
        <tr>
            <th>Nom de code</th>
        </tr>
        <?php foreach ($plugins['removed'] as $plugin): ?>
        <tr>
            <td><?php echo $plugin; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endforeach; ?>

    <?php foreach ($report['certified'] as $version => $plugins): ?>
    <h1>Netbeans v<?php echo $version; ?></h1>

    <h2><?php echo count($plugins['downloaded']); ?> nouveaux plugins</h2>
    <table>
        <tr>
            <th>Nom de code</th>
            <th>Nom</th>
            <th>Date de sortie</th>
            <th>Taille</th>
        </tr>
        <?php foreach ($plugins['downloaded'] as $plugin): ?>
        <tr>
            <td><?php echo $plugin['codename']; ?></td>
            <td><?php echo $plugin['name']; ?></td>
            <td><?php echo $plugin['releaseDate']; ?></td>
            <td class="right"><?php echo $plugin['size']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2><?php echo count($plugins['not_downloaded']); ?> plugins existants</h2>
    <table>
        <tr>
            <th>Nom de code</th>
            <th>Nom</th>
            <th>Date de sortie</th>
            <th>Taille</th>
        </tr>
        <?php foreach ($plugins['not_downloaded'] as $plugin): ?>
        <tr>
            <td><?php echo $plugin['codename']; ?></td>
            <td><?php echo $plugin['name']; ?></td>
            <td><?php echo $plugin['releaseDate']; ?></td>
            <td class="right"><?php echo formatBytes($plugin['size']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endforeach; ?>

    <?php foreach ($report['distribution'] as $version => $plugins): ?>
    <h1>Netbeans v<?php echo $version; ?></h1>

    <h2><?php echo count($plugins['downloaded']); ?> nouveaux plugins</h2>
    <table>
        <tr>
            <th>Nom de code</th>
            <th>Nom</th>
            <th>Date de sortie</th>
            <th>Taille</th>
        </tr>
        <?php foreach ($plugins['downloaded'] as $plugin): ?>
        <tr>
            <td><?php echo $plugin['codename']; ?></td>
            <td><?php echo $plugin['name']; ?></td>
            <td><?php echo $plugin['releaseDate']; ?></td>
            <td class="right"><?php echo $plugin['size']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2><?php echo count($plugins['not_downloaded']); ?> plugins existants</h2>
    <table>
        <tr>
            <th>Nom de code</th>
            <th>Nom</th>
            <th>Date de sortie</th>
            <th>Taille</th>
        </tr>
        <?php foreach ($plugins['not_downloaded'] as $plugin): ?>
        <tr>
            <td><?php echo $plugin['codename']; ?></td>
            <td><?php echo $plugin['name']; ?></td>
            <td><?php echo $plugin['releaseDate']; ?></td>
            <td class="right"><?php echo formatBytes($plugin['size']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
