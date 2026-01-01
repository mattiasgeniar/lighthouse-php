<?php

declare(strict_types=1);

use Spatie\Lighthouse\Lighthouse;

beforeEach(function () {
    $this->tempFilesBefore = glob(sys_get_temp_dir() . '/lighthouse.*');
});

afterEach(function () {
    $testArtifacts = array_diff(
        glob(sys_get_temp_dir() . '/lighthouse.*'),
        $this->tempFilesBefore
    );
    foreach ($testArtifacts as $dir) {
        if (is_dir($dir)) {
            @rmdir($dir);
        }
    }
});

it('cleans up lighthouse temp directories after successful run', function () {
    Lighthouse::url('https://example.com')
        ->timeoutInSeconds(60)
        ->run();

    // Give cleanup time to complete
    sleep(1);

    $tempFilesAfter = glob(sys_get_temp_dir() . '/lighthouse.*');
    $newTempFiles = array_diff($tempFilesAfter, $this->tempFilesBefore);

    expect($newTempFiles)->toBeEmpty();
});
