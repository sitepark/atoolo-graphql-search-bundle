<?php

declare(strict_types=1);

namespace Atoolo\GraphQL\Search\Test\Resolver;

use Atoolo\GraphQL\Search\Resolver\DoNothingUrlRewriter;
use Atoolo\GraphQL\Search\Resolver\UrlRewriterType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DoNothingUrlRewriter::class)]
class DoNothingUrlRewriterTest extends TestCase
{
    public function testRewrite(): void
    {
        $rewriter = new DoNothingUrlRewriter();
        $this->assertEquals(
            'url',
            $rewriter->rewrite(UrlRewriterType::LINK, 'url'),
        );
    }
}
