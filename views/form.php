<form method="post" action="">
    <fieldset>
        <legend>What do you want to update ?</legend>
        <div>
            <input type="checkbox" id="update-plugins" name="options[update_plugins]" value="1"<?php echo $config['updatePlugins'] ? ' checked="checked"' : ''; ?> />
            <label for="update-plugins">Plugins</label>
        </div>
        <div>
            <input type="checkbox" id="update-certified" name="options[update_certified]" value="1"<?php echo $config['updateCertified'] ? ' checked="checked"' : ''; ?> />
            <label>Certified plugins</label>
        </div>
        <div>
            <input type="checkbox" id="update-distribution" name="options[update_distribution]" value="1"<?php echo $config['updateDistribution'] ? ' checked="checked"' : ''; ?> />
            <label>Netbeans distributions</label>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Which Netbeans versions ?</legend>
        <div class="plugins">
            <div class="header"><label>For plugins</label></div>
            <div class="header"><textarea name="options[plugins_versions]" rows="3" style="resize: vertical"><?php echo implode("\n", $config['pluginsVersions']); ?></textarea></div>
        </div>
        <div class="certified distribution">
            <div class="header"><label>For certified plugins and distributions</label></div>
            <div class="header"><textarea name="options[netbeans_versions]" rows="3" style="resize: vertical"><?php echo implode("\n", $config['netbeansVersions']); ?></textarea></div>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>URL & Folders</legend>
        <div class="subfieldset plugins">
            <h3>Plugins</h3>
            <div>
                <div class="header"><label>Mirror URL</label></div>
                <div class="header"><input type="text" name="options[mirror_plugins_url]" value="<?php echo $config['mirrorPluginsUrl']; ?>" size="100" /></div>
            </div>
            <div>
                <div class="header"><label>Target folder</label></div>
                <div class="header"><input type="text" name="options[plugins_target_dir]" value="<?php echo $config['pluginsTargetDir']; ?>" size="100" /></div>
            </div>
            <div>
                <div class="header"><label>Archive folder</label></div>
                <div class="header"><input type="text" name="options[plugins_archive_dir]" value="<?php echo $config['pluginsArchiveDir']; ?>" size="100" /></div>
            </div>
        </div>
        
        <div class="subfieldset certified">
            <h3>Certified plugins</h3>
            <div>
                <div class="header"><label>Mirror URL</label></div>
                <div class="header"><input type="text" name="options[mirror_certified_url]" value="<?php echo $config['mirrorCertifiedUrl']; ?>" size="100" /></div>
            </div>
            <div>
                <div class="header"><label>Target folder</label></div>
                <div class="header"><input type="text" name="options[certified_target_dir]" value="<?php echo $config['certifiedTargetDir']; ?>" size="100" /></div>
            </div>
            <div>
                <div class="header"><label>Archive folder</label></div>
                <div class="header"><input type="text" name="options[certified_archive_dir]" value="<?php echo $config['certifiedArchiveDir']; ?>" size="100" /></div>
            </div>
        </div>
        
        <div class="subfieldset distribution">
            <h3>Netbeans distributions</h3>
            <div>
                <div class="header"><label>Mirror URL</label></div>
                <div class="header"><input type="text" name="options[mirror_distribution_url]" value="<?php echo $config['mirrorDistributionUrl']; ?>" size="100" /></div>
            </div>
            
            <div>
                <div class="header"><label>Target folder</label></div>
                <div class="header"><input type="text" name="options[distribution_target_dir]" value="<?php echo $config['distributionTargetDir']; ?>" size="100" /></div>
            </div>
            <div>
                <div class="header"><label>Archive folder</label></div>
                <div class="header"><input type="text" name="options[distribution_archive_dir]" value="<?php echo $config['distributionArchiveDir']; ?>" size="100" /></div>
            </div>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Miscellaneous</legend>
        <div>
            <input type="checkbox" name="options[archive_old_files]" value="1"<?php echo $config['archiveOldFiles'] ? ' checked="checked"' : ''; ?> />
            <label>Archive old files</label>
        </div>
        <div>
            <input type="checkbox" name="options[pack_files]" value="1"<?php echo $config['packFiles'] ? ' checked="checked"' : ''; ?> />
            <label>Pack repository files after download (in a netbeans.tar file)</label>
        </div>
        <div>
            <input type="checkbox" name="options[compress_packed_files]" value="1"<?php echo $config['compressPackedFiles'] ? ' checked="checked"' : ''; ?> />
            <label>Compress packed files (in a netbeans.tar.gz file)</label>
        </div>
    </fieldset>
    
    <div class="controls">
        <div>
            <button name="options[submit]" value="scan" type="submit">Scan for updates</button>
        </div>
        <div>
            <button name="options[submit]" value="download" type="submit">download</button>
        </div>
    </div>
</form>