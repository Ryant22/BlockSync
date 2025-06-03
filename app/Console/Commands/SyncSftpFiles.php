<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class SyncSftpFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sftp:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize files with SFTP server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting SFTP file synchronization...');

        $sftp = \Storage::disk('sftp');
        $local = \Storage::disk('local');

        $remoteFiles = $sftp->files();
        
        foreach ($remoteFiles as $file) {
            $this->info("Processing file: $file");

            // Always use forward slashes for paths
            $normalizedFile = str_replace('\\', '/', $file);

            // Get file contents from SFTP
            $contents = $sftp->get($normalizedFile);

            // Check if the file exists locally
            if (!$local->exists($normalizedFile)) {
                // Save the file to local storage using Laravel's Storage API
                $local->put($normalizedFile, $contents);
                $this->info("Downloaded: $file to local disk");
            } else {
                $this->info("File already exists locally: $file");
                // check if the local file is outdated
                $remoteLastModified = $sftp->lastModified($normalizedFile);
                $localLastModified = $local->lastModified($normalizedFile);
                if ($remoteLastModified > $localLastModified) {
                    // Update the local file if the remote file is newer
                    $local->put($normalizedFile, $contents);
                    $this->info("Updated local file: $file");
                } else {
                    $this->info("Local file is up-to-date: $file");
                }
            }
        }
    }
}
