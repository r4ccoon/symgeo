<?php
namespace AppBundle\Controller;
/**
 * Class ManageController
 *
 * @route("/manage")
 */
class ManageController extends BaseController
{
	/**
	 * @Route("/{companySlug}/", name="manage_company_index")
	 */
	public function manageCompanyAction($companySlug)
	{
		return $this->render(
			'manage/main.html.twig'
		);
	}

	/**
	 * @Route("/{companySlug}/fleet/", name="manage_fleet")
	 */
	public function manageFleetAction($companySlug)
	{
		return $this->render(
			'manage/company.html.twig'
		);
	}
}