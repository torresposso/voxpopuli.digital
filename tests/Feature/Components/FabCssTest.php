<?php

/**
 * Regression tests for the FAB component CSS.
 *
 * The original bug: sub-buttons "disappeared when clicking" because the
 * fade-in animation had pointer-events:auto enabled during the semi-
 * transparent window, allowing phantom clicks on invisible elements.
 *
 * The fix: visibility transitions with delay so the sub-buttons are not
 * clickable during fade-in, and `.fab-sub-button` no longer uses
 * `transition: all` (which was overriding the 0.2s opacity transition
 * with a 0.4s one).
 */

beforeEach(function () {
    $path = realpath(__DIR__ . '/../../../web/app/themes/voxpopuli/resources/views/components/fab.blade.php');
    $this->fabPath = $path ?: 'NOT FOUND';
    $this->fabExists = (bool) $path;
    $this->fabSize = $path ? filesize($path) : 0;
    $this->fab = $path ? (string) file_get_contents($path) : '';
});

it('declares visibility: hidden on closed sub-buttons to block phantom clicks during fade-in', function () {
    expect($this->fabExists)->toBeTrue("FAB file not found at: {$this->fabPath}");
    expect($this->fabSize)->toBeGreaterThan(0, "FAB file is empty (size={$this->fabSize}) at: {$this->fabPath}");
    expect(strlen($this->fab))->toBeGreaterThan(100, "FAB content too short (" . strlen($this->fab) . " chars)");

    expect($this->fab)
        ->toContain('.fab button,')
        ->toContain('.fab a {')
        ->toContain('visibility: hidden');
});

it('declares visibility: visible on active sub-buttons with no delay', function () {
    expect($this->fab)
        ->toContain('.fab.fab-active button,')
        ->toContain('visibility: visible');
});

it('applies a visibility transition-delay when closing so the button stays clickable during fade-out', function () {
    // The fade-out transition should include a visibility delay matching
    // the opacity duration, so the button only becomes hidden AFTER the
    // opacity reaches 0.
    expect($this->fab)
        ->toMatch('/transition:\s*opacity\s+0\.2s,\s*visibility\s+0s\s+linear\s+0\.2s/');
});

it('does not use transition: all on .fab-sub-button (which was overriding the 0.2s opacity transition)', function () {
    // Extract just the .fab-sub-button block and check it.
    preg_match('/\.fab-sub-button\s*\{[\s\S]+?\}/', $this->fab, $m);
    expect($m)->toHaveCount(1);

    expect($m[0])
        ->not->toMatch('/transition:\s*all/');
});

it('explicitly lists opacity in the .fab-sub-button transition so the 0.2s fade is preserved', function () {
    preg_match('/\.fab-sub-button\s*\{[\s\S]+?\}/', $this->fab, $m);
    expect($m[0])
        ->toMatch('/transition:.*\bopacity\s+0\.2s\b/');
});
