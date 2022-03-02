<?php

namespace Freshbitsweb\LaravelLogEnhancer;

class RequestDataProcessor
{
    /**
     * Adds additional request data to the log message.
     */
    public function __invoke($record)
    {
        if (config('laravel_log_enhancer.log_input_data')) {
            $record['extra']['inputs'] = request()->except(config('laravel_log_enhancer.ignore_input_fields'));
        }

        if (config('laravel_log_enhancer.log_request_headers')) {
            $record['extra']['headers'] = request()->header();
        }

        if (config('laravel_log_enhancer.log_session_data')) {
            $record['extra']['session'] = session()->all();
        }

        if (config('laravel_log_enhancer.log_git_data')) {
            $record['extra']['git'] = $this->getGitDetails();
        }

        if (config('laravel_log_enhancer.log_app_details')) {
            $record['extra']['Application Details'] = [
                'Laravel Version' => app()::VERSION,
                'PHP Version' => phpversion(),
                'Config Cached' => app()->configurationIsCached() ? 'Yes' : 'No',
                'Route Cached' => app()->routesAreCached() ? 'Yes' : 'No',
            ];
        }

        return $record;
    }

    public function getGitDetails()
    {
        $gitDetails = [];
        $lastCommitDetails = `git show -s --format=%B`;
        $gitDetails['Last Commit Message'] = preg_filter("/(.*?)\n*/s", '\\1', $lastCommitDetails);

        $currentHeadDetails = `git branch -v --no-abbrev`;
        if (
            $currentHeadDetails &&
            preg_match('{^\* (.+?)\s+([a-f0-9]{40})(?:\s|$)}m', $currentHeadDetails, $matches)
        ) {
            $gitDetails['branch'] = $matches[1];
            $gitDetails['commit'] = $matches[2];
        }

        $stagedChanges = `git diff --cached`;
        if ($stagedChanges) {
            $gitDetails['warning'][] = 'Last commit is dirty. Staged changes have been made since this commit.';
        }

        $unStagedChanges = `git diff`;
        if ($unStagedChanges) {
            $gitDetails['warning'][] = 'Last commit is dirty. (Un)staged changes have been made since this commit.';
        }

        return $gitDetails;
    }
}
