<?php

namespace Postman\PostmanBundle\Tests\Parser;

use Postman\PostmanBundle\Parser\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $parser;

    protected function setUp()
    {
        $this->parser = new Parser;
    }

    /**
     * @dataProvider getData
     */
    public function testParser($subject, $from, $to, $toName, $body, $attachmentsCount, $mailSource)
    {
        $raw = file_get_contents($mailSource);
        $mail = $this->parser->parse($raw);

        $this->assertEquals($subject, $mail->getSubject());
        $this->assertEquals($from, $mail->getFrom());
        $this->assertEquals($to, $mail->getTo());
        $this->assertEquals($toName, $mail->getTo(true));
        $this->assertContains($body, $mail->getBody());
        $this->assertCount($attachmentsCount, $mail->getAttachments());
    }

    public function getData()
    {
        return array(
            array(
                'Subject',
                'megazoll@googlemail.com',
                'megazoll@gmail.com',
                'megazoll',
                'test',
                0,
                __DIR__.'/../Resources/1.txt'
            ),
            array(
                'subj',
                'megazoll@googlemail.com',
                'megazoll@gmail.com',
                'megazoll',
                'zip',
                1,
                __DIR__.'/../Resources/2.txt'
            ),
        );
    }
}
