<?php

declare(strict_types=1);

if (PHP_VERSION_ID < 80500) {
    http_response_code(503);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>PHP 8.5 required</title></head><body>';
    echo '<h1>PHP 8.5 or newer is required</h1>';
    echo '<p>Detected version: ' . htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '</body></html>';
    exit;
}

/** @return list<string> */
function feature_slugs(): array
{
    return [
        'pipe-operator',
        'clone-with',
        'array-first-last',
        'uri-extension',
        'nodiscard',
    ];
}

/** @param list<string> $items */
function join_feature_labels(array $items): string
{
    return implode(' · ', $items);
}

/** @param list<string> $slugs */
function format_feature_labels(array $slugs): string
{
    $labels = array_map(
        static fn(string $slug): string => str_replace('-', ' ', ucwords($slug, '-')),
        $slugs,
    );

    return $labels
        |> array_map(static fn(string $label): string => trim($label), ...)
        |> join_feature_labels(...);
}

$primarySlug = feature_slugs() |> array_first(...);
$latestSlug = feature_slugs() |> array_last(...);
$featureSummary = format_feature_labels(feature_slugs());
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$pageTitle = 'PHP 8.5 Landing';
$year = (new DateTimeImmutable('now'))->format('Y');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A single-file landing page powered by PHP 8.5">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root {
            --bg: #0b1020;
            --surface: #121a2f;
            --surface-2: #1a2440;
            --text: #e8eefc;
            --muted: #9fb0d4;
            --accent: #7c5cff;
            --accent-2: #3dd6c6;
            --border: rgba(255, 255, 255, 0.08);
            --shadow: 0 24px 80px rgba(0, 0, 0, 0.45);
            --radius: 18px;
            --max: 1120px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(124, 92, 255, 0.28), transparent 60%),
                radial-gradient(900px 500px at 90% 0%, rgba(61, 214, 198, 0.18), transparent 55%),
                var(--bg);
            line-height: 1.6;
        }

        a { color: inherit; text-decoration: none; }

        .wrap {
            width: min(100% - 2rem, var(--max));
            margin-inline: auto;
        }

        header {
            padding: 1.25rem 0 2rem;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .logo {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--accent), #4f7dff);
            box-shadow: var(--shadow);
            font-size: 0.95rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.7rem;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.03);
            color: var(--muted);
            font-size: 0.85rem;
        }

        .badge strong { color: var(--text); }

        .hero {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 2rem;
            align-items: center;
            padding: 2rem 0 4rem;
        }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
        }

        h1 {
            font-size: clamp(2.2rem, 5vw, 3.6rem);
            line-height: 1.08;
            margin: 0 0 1rem;
            letter-spacing: -0.03em;
        }

        .gradient {
            background: linear-gradient(90deg, #fff 0%, #c9d6ff 45%, var(--accent-2) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .lead {
            color: var(--muted);
            font-size: 1.08rem;
            max-width: 38rem;
            margin: 0 0 1.5rem;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.85rem 1.15rem;
            border-radius: 12px;
            font-weight: 600;
            border: 1px solid transparent;
            transition: transform 0.15s ease, background 0.15s ease;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #5b7dff);
            color: white;
            box-shadow: 0 12px 30px rgba(124, 92, 255, 0.35);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.04);
            border-color: var(--border);
            color: var(--text);
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
        }

        .panel h2 {
            margin: 0 0 0.75rem;
            font-size: 0.95rem;
            color: var(--muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .kv {
            display: grid;
            gap: 0.65rem;
        }

        .kv div {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.65rem 0.75rem;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.18);
            font-size: 0.92rem;
        }

        .kv span:last-child {
            color: #d7e2ff;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            text-align: right;
        }

        section {
            padding: 1rem 0 4rem;
        }

        .section-title {
            margin: 0 0 0.35rem;
            font-size: 1.5rem;
        }

        .section-lead {
            margin: 0 0 1.5rem;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.15rem;
            min-height: 100%;
        }

        .card h3 {
            margin: 0 0 0.5rem;
            font-size: 1.05rem;
        }

        .card p {
            margin: 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .code {
            margin-top: 2rem;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .code header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--muted);
            font-size: 0.85rem;
        }

        pre {
            margin: 0;
            padding: 1rem 1.1rem 1.2rem;
            overflow-x: auto;
            font-size: 0.88rem;
            line-height: 1.55;
            color: #d9e4ff;
        }

        footer {
            padding: 2rem 0 3rem;
            color: var(--muted);
            border-top: 1px solid var(--border);
            font-size: 0.92rem;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <nav class="nav" aria-label="Primary">
                <div class="brand">
                    <div class="logo" aria-hidden="true">8.5</div>
                    <span><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="badge">
                    Running <strong>PHP <?= htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </nav>

            <div class="hero">
                <div>
                    <h1>Build faster with <span class="gradient">PHP 8.5</span></h1>
                    <p class="lead">
                        A single-file landing page that uses PHP 8.5 language features — pipe operator,
                        <code>array_first()</code>, <code>array_last()</code>, and strict typing — with no framework required.
                    </p>
                    <div class="actions">
                        <a class="btn btn-primary" href="https://www.php.net/releases/8.5/en.php" target="_blank" rel="noopener noreferrer">
                            Explore PHP 8.5
                        </a>
                        <a class="btn btn-secondary" href="#runtime">View runtime</a>
                    </div>
                    <div class="meta">
                        <span>First feature: <?= htmlspecialchars($primarySlug, ENT_QUOTES, 'UTF-8') ?></span>
                        <span>Latest feature: <?= htmlspecialchars($latestSlug, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>

                <aside class="panel" id="runtime" aria-labelledby="runtime-heading">
                    <h2 id="runtime-heading">Runtime snapshot</h2>
                    <div class="kv">
                        <div><span>PHP version</span><span><?= htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8') ?></span></div>
                        <div><span>SAPI</span><span><?= htmlspecialchars(PHP_SAPI, ENT_QUOTES, 'UTF-8') ?></span></div>
                        <div><span>Host</span><span><?= htmlspecialchars($host, ENT_QUOTES, 'UTF-8') ?></span></div>
                        <div><span>Request URI</span><span><?= htmlspecialchars($requestUri, ENT_QUOTES, 'UTF-8') ?></span></div>
                        <div><span>Feature chain</span><span><?= htmlspecialchars($featureSummary, ENT_QUOTES, 'UTF-8') ?></span></div>
                    </div>
                </aside>
            </div>
        </header>

        <section aria-labelledby="features-heading">
            <h2 class="section-title" id="features-heading">What is new in 8.5</h2>
            <p class="section-lead">Highlights from the PHP 8.5 release, rendered server-side from this one file.</p>

            <div class="grid">
                <article class="card">
                    <h3>Pipe operator</h3>
                    <p>Chain transformations left-to-right with <code>|&gt;</code> instead of nested calls.</p>
                </article>
                <article class="card">
                    <h3>Clone with</h3>
                    <p>Update properties while cloning — ideal for immutable and readonly value objects.</p>
                </article>
                <article class="card">
                    <h3>Array helpers</h3>
                    <p><code>array_first()</code> and <code>array_last()</code> replace common boilerplate safely.</p>
                </article>
                <article class="card">
                    <h3>URI extension</h3>
                    <p>Parse and normalize URLs with a built-in extension aligned to modern standards.</p>
                </article>
                <article class="card">
                    <h3>#[\NoDiscard]</h3>
                    <p>Warn when important return values are ignored, improving API safety.</p>
                </article>
                <article class="card">
                    <h3>Better debugging</h3>
                    <p>Fatal errors include backtraces; new handler getters improve observability.</p>
                </article>
            </div>

            <div class="code" role="region" aria-label="PHP 8.5 example">
                <header>index.php — pipe operator in this page</header>
                <pre><code><?= htmlspecialchars(<<<'PHP'
$featureSummary = format_feature_labels(feature_slugs());

$primarySlug = feature_slugs() |> array_first(...);
$latestSlug  = feature_slugs() |> array_last(...);
PHP, ENT_QUOTES, 'UTF-8') ?></code></pre>
            </div>
        </section>

        <footer>
            <p>&copy; <?= htmlspecialchars($year, ENT_QUOTES, 'UTF-8') ?> PHP 8.5 Landing · Single file · No dependencies</p>
        </footer>
    </div>
</body>
</html>
