<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuickbookToken extends Seeder {

    public function run() {
        DB::table('quickbook_token')->truncate();
        DB::table('quickbook_token')->insert(
                array(
                    //All inclusive account
                    array(
                        'id' => '1',
                        'client' => 'ABqWNJ9oSzeRQK3nuJW4HliFuAZ3D2hZjwVvZPgjvn6cWgAyYV',
                        'secret' => 'qEa5lFHYi1lSlbqjZLGogdM4B23bs78DJK3glnmq',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..qkoUbOrKmEoEQr0Cr3xZTA.9AOJCZ70TYYU2Wu6wUdaoydrdq9zb1FVirSsMob_3dgq2NlQuPw3qD5GmfgQh1N6jBMjnp9n8vh8LMw0oZZWpRkQ2FMWn0PHy5Xx58DztA4c2eJzyrLQodeN5BsdXkpYRCIb8gZkvqkIh1wHT_nhz8s5-CJesCbohB9l7_iEuHhTMCPSHoQWK2M2cXNQl-phkM0cPTHHdeHVgzghmI00VSdfR8mVcOB2mbb6SK2Fh8InkHMNT5bKimkmRRpPeV5dBewigu3VRU5UR7Nq2d3EFHO6FwAoI_OdbCObKdngBYNzKIoXgtcUAUxN4iE2YmGPwJ4KVYjbsuR8P4PdJ-pMxE1kmTgQb61xfs1qZK_kCbFUThHrVqfO9ljQEv8ynwnL6bzM1qrmx4nbXT6V-YBLksizS3Mm2WfzSnHBIpgH5y2-KWVKuu0YUsgDz-8tgsGM7Ddp1GLrf-40jxULMErkAqAhuTmLc5oBDkjqXlFNVFIK9wJ1zhXh5EA5IRU79CpAlRi_1bFHSeCF9BApX6_Z6WFBGpemu0LZnYQuUaL1WGOkeRjdpIos0zbaojwKEfr5SrvjMt5q3Zhq2H-lXnPYE4EF77nbIExidJw8-01d3JJYawNmjCqLYNdXeGeLiKgQgbjHZTqc0F6dlJbD3zi3p0TZjeow_ut1n9SatlSBFo1o27bDWWIuCcWelzQ3YazOf5as56Tc0wOWI19FHO6WVwAbpW0DLUaiGmGOSN3Hy58.ui_Be9_LU1l8nINLR9flTw',
                        'refresh_token' => 'AB11582371924mfKhlZKkftfhljuFtdIBQX3jUTcUDH1oI4Mxo'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABELoVA6ZT7evmeiiho5stnNA3jvAv4G6PnMEHoHr162ToCqE1',
                        'secret' => 'ibVkuQYEUn7aHGdVdJr6sx9MddtEWSRkm5d3c4SL',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..DW2eZ4amGDqcxPxbzATTDQ.S7z-po_kgBolbCOSNcI5cpeczTGdA14xdXYNPrBbe12A59AyZ9OE9gTvoiLfcTVdoWCR-yQfnPYovmp9g5OxVoSLdD35Y9yCdrUrHlv2-xXWyE7RFrMM96amfbeN9Na98Tv5bpl3P2IcxO4tEymDe5SuMg-HtbGtZWAFzi139n2yaRNHiJs1m5h2Fn0QXhziTdlSPCqrGnljIkQi9qVOYw4ac8pXF8uqm5qEMzItRmZZZtEm6jcpObspJTaACYyp_9VaNmuykgmM2UQjBeOHowJ3IVziB3l-2fsIaq0RswCGqkql1XloV5FFWnR1nTkMDYNdleQS01xtXU5Hr9qD-zAxDp53VC8rlI8p9vLuuV5v13B-dZ1j7QvwtHKM3SLG_maQOAnHk5qShBf4emLrjzav8HZgRe8MzY6acSWJrQc3mYRYSNpJNygtrw57gUK_g6B4TAlZd6MZo20unKU6jfiq3DNhsYqo6jOyV15sEUxMgXfoOasN8Gk9_tcIqsNwJBr2lHU2taJCByaOUvnAEYgul6uqqmDLpElc8RCUUd9iCNvznDGt5PLmLVu5KiJJdsFFK5vdUwdlCVOzxyATz-U2nXV4IVbeVyr2bxCIpat1X1WFCV3OZVzlbOLFXQg-RXbgMqtUZrBLoJc_yr1I9NQCu_LJVAWEj_pN8FDPsJrTwIFXW9LYE3q2Zdi7-QOumszkKbgKlEXyin--5dJM70zrD4U7rPiRbHpqvaNVNnY.FCK9pzwCj9iCO58zBKXsjA',
                        'refresh_token' => 'AB11582371852TZ2wHbjm9yNcoAgXkJpMWXB5hCggmzl6Y4tzN'
                    )
        ));
    }

}
