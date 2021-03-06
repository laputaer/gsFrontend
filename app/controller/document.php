<?php

/*
 * gsFrontend
 * Copyright (C) 2011 Gedankengut GbR Häuser & Sirin <support@gsales.de>
 * 
 * This file is part of gsFrontend.
 * 
 * gsFrontend is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * gsFrontend is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with gsFrontend. If not, see <http://www.gnu.org/licenses/>.
 */

class FRONTEND_CONTROLLER_DOCUMENT extends FRONTEND_CONTROLLER {

	public function __construct(){
		parent::__construct();
	}
	
	public function indexAction(){
		
		$objDataDocs = new GSALES_DATA_DOCUMENT();
		$arrayOfObjDocs = $objDataDocs->getDocumentsForCustomerId($this->objUserAuth->getCustomerId()); // get public documents for customer
		$this->objSmarty->assignByRef('docs', $arrayOfObjDocs);
		
	}
	
	public function fileAction(){
		
		$this->setSmartyOutput(false);
		
		$arrUserRequest = $this->getUserRequest();
		if (false == isset($arrUserRequest['params']['0']) || false == is_numeric($arrUserRequest['params']['0'])){
			$this->redirectTo('document'); // check for refund id to get pdf for
			return;
		}
		
		$intDocumentId = $arrUserRequest['params']['0'];
		$objDataDocument = new GSALES_DATA_DOCUMENT();
		$arrPDF = $objDataDocument->getDocumentFile($intDocumentId, $this->objUserAuth->getCustomerId());
		
		if (false == $arrPDF){
			$this->redirectTo('document');
			return;
		}
		
		header('Content-type: application/download');
		header('Content-Disposition: attachment; filename="'.$arrPDF['filename'].'"');
		echo $arrPDF['content'];
		
	}

}