<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuickbookToken extends Seeder {

    public function run() {
        DB::table('quickbook_token')->insert(
                array(
                    //All inclusive account
                    array(
                        'id' => '2',
                        'client' => 'Q0RdWcaasGzlKnDFpr39BHICZX2ve0n4cfSUJJeB0XsoDP5C6X',
                        'secret' => 'c66GKMcvFSUc0hgtQBqIhkZnKGZAmwyRBWeikcJa',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..rvTVS2mvza-6IlEVw3WAbg.H00Rk6U6bsY8z-wgX3JFZfLl_zV7PIbDc5prnkYqzVe-vuSt5u-NrpUcjk8Wg_QH6ez5oQtfEzwJz190ryVS-icNNOETAPdlAJ5-N1TAxeSSMTVmVoWG4UOA6coZKyTjIMlVpoHvJU7ZAujm7jkmtl3s8owmfMwN0N79vKdo3X7jGsjVifLMjsq6T_JP5bo-RlQ8msYcMHEFmcbgJ_pqnURgGD2cNQi27ZLngSl1XrP82b9ZCNn28k0poAyhKTFmF1fdb9n9rA6ViSq5kxrSihztfiFfRdp4WDhS2KsNdS2W4oTrD-Rek-E-lduAkHIyrNbAKN94UPLN15JjLQTKp3P6Y4ZkYACff2UuWTlYafRQDnTrfY8GJQXwWn271FLE7Hfqf3qTQqevhkhY_w2QV8UHgqwDu05dizkUQDIPPfqmYcjSyt5D2Hws7VbJzpMjJ6slloWK3qk2G0_z8d35wgLdQZYfBeOlSH2BYFPNXC7t6gFhedysuHKTpEaTT-lIaH70jYzznlj6LoBIavLwIUwJ0MZ8wVwvuoxWgxWbLDLw6OhEJTee2KrPoURkyq_Z-sgl7BWZVTz9fTSQEL9Q8xZ3cStd7s_C8ObRvmHQm1GSapB4y99Dt2Umaak2qbwpf90ITcosla-_VuUWEQGDQX_9M7Z5JwJJ77BZB8sdc57mZZ6XejxYgUyGdRGdREiT.VX03QFEfI32lfCg4rhnFlA',
                        'refresh_token' => 'Q011567509815qwSpzLxLkqLAwL0rrvhnCa9BrLMFl3ytOywUc'
                    )
        ));
    }

}
