<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuickbookTokens extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			  DB::table('quickbook_token')->where('id', "3") 
            ->update(array(
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..rwMcL_6xJ8Bv7ddighNZOA.sPDIoNfUTcQ__Dk1Rr2fNn2L0XmHplSmXC2vFG7bZXZWQpKFQ2gDY0iDW7HuMzfJGjDceYpeIngqf05y4P95IQWWPS9b1YwJT6SiJQlDDw-2vROZ1Hky2i97z5OAB3_UwC8Fh3NbgCSr5AIRHnKKs34tEO29khGN9dOyho6wj5rxkpnsEnFXRQYbtw2yITSrpme23xEGmrw-5xI1Ec7oEjP6Z93-lqI4EWlX0MfauD1NVzuOMisKqY21du7pfga0l_g-EtfFvYJTdmdjyEYel6Wym2Jj1vfGOg_M6quGApVM-QpYGO9QggQ2yRKGDoArsaiGdgcAHpslatOn-WHP4uVVkgYlM49LCT2Q3IGus4G1Xen2UYMO2oRb18Aa0cvqeFDneZQZ2enbAvX9-VcmHO5APaZBSQoJRR_GOWSnpaeindWJBVti3JDe7rpeqApfB8Z1yfLc0QCjy0RXqmZvlkLmOdFPqwkBf171Xi58PXCjur5ds1qeu7KYIj8eWctpc-qKVheLl71j_F4OEHUmasQHglpb0VHBlcAVQxSRrT_U-v0krG5Z8khfI0fH1_D6Xu37it98kBpn_csqxhlfdxCMmCWhz7i8jZSqjouXQwVTzY8CHdsSBZ8qFFs2g90oDXn-E_XMJE3Don4_Ra3OfNRENuzdIwnv4H4DLEAZjsyschI696GqhKgIXlfc22zkyhhFyvg8N-mlR2dRpwJyLTdHA06OmZeRKfCHk3IZxiM.hAFYMBxcT-lFIA9xiZpoLw',
                        'refresh_token' => 'AB11575722433i0eHEesFrkekEtIFBXb9L9zEeraTmBDASOGqR'
                    )
                    );
		  DB::table('quickbook_token')->where('id', 4)
            ->update(array(
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..4JEyAPim2k30U19GOkEHPA.Ebk6UzjKHaC_qtTqQ4oPzw_R4_OoO2kBQTcnUJq-u7om2LjSD2zgw7blowLhCg3rCK9RJgwF6iiBMbaethC16LOzwbemNwAXEsUIyOjPtP-_bHGJLWqgW05J3OFIN8VeJsM__eSQpjVo3l5lUVsOJreww-6MefPqR1Chumzya4HvfF1UOpYyhda6q_-AdT1ApIilOlo0fWdwnG5z8UCafIYyUtMhgTQuO6sJ3HGYrtcH2on44Q628yMKDH06a4Yye3iwCVdv28-mRt0iV625EPwfTBR-pAfHH3CTZUFul3v6el_oMNJWbEtBppJfwA6nj2vWL-6l3BKLjBlXcF49CBOoNoFysaflmNTAdBgPXiLMIKiD2882tURtlBxQKeOLlG_rm8SoVhPpsExaFEdUZWSI8X0K4c0dyoSGL9POtk3GmJDwBB1mBkld4XCEBuvSTRmoIwzFkWSVZrp4yCbHNJDIhKrFrB1CZQf7IfF3mm06WF1ehXkCEtg6zFH2-EIQAE4VntAOHj_jnsZtRPZN3P-SczJObclGBxlXPtMayqX5Z_UC7O4PSaYPL5zYUifkcDYbi5U5nfv7h8hVrbKOgTF_53pOvMV2cZJugh1D6l4pJ-EcrqR5y7OuizfIIv0XDZSy0L5kSssYmaHYuPupkz5xFazp6tEBucMMi-Eg71qd0xgqft3tLLZdlXoHaauzBePaBUB19bExIY4v30kiobUG4fgxMKTP2E_K7d6fFFg.gPY-ltu-hvLxO7UKKN4RVg',
                        'refresh_token' => 'AB11575722531bXblz7ig8614TTHlJ924BX1ODZZAZ2qw07Wk4'
                    )
                    );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
	}

}
