<?php


namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\Unit;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class UnitDatatable
 * @package App\Datatables\Tables
 */
class UnitDatatable extends AbstractDatatable{

    public function getLineFormatter()
	{
		return function ($row) {
			$unit = $this->getEntityManager()->getRepository('App:Unit')->find($row['id']);
			$html = '<ul>';

			$html1 = '<ul>';
			foreach ($unit->getActivities() as $activity) {
				$html .= '<li>' . $activity->getName() . '</li>';
				$html1 .= '<li>' . $activity->getPage() . '</li>';
			}
			$html .= '</ul>';
			$html1 .= '</ul>';
			$row['activities'] = $html;
			$row['pages'] = $html1;
			return $row;
		};
	}


    /**
     * @param array $options
     * @throws \Exception
     */
    public function buildDatatable(array $options = []){
        $this->ajax->set([
			'url' => $options['url'],
			'method' => 'POST',
		]);

		$this->options->set([
			'classes' => Style::BOOTSTRAP_4_STYLE,
			'individual_filtering' => false,
			'order_cells_top' => true,
		]);
		$this->extensions->set(array(
			'responsive' => true,
		));
		$this->features->set([
			'processing' => true,
		]);

		$this->columnBuilder
			->add('uuid', Column::class, [
				'title' => 'uuid',
				'visible' => false,
			])
            ->add('book.title', Column::class, [
				'title' => 'Curso',
			])
            ->add('name', Column::class, [
				'title' => 'Nombre de la unidad',
			])
            ->add('pdf.originalName', Column::class, [
				'title' => 'Nombre del acrchivo',
			])
            ->add('activities', VirtualColumn::class, [
				'title' => 'Recursos',
			])
			->add('pages', VirtualColumn::class, [
				'title' => 'Páginas',
			])
            ->add(null, ActionColumn::class, [
				'title' => $this->translator->trans('sg.datatables.actions.title'),
				'actions' => [
					TableActions::add('unit_activities'),
					TableActions::edit('unit_edit'),
                    TableActions::delete('unit_delete'),
				],
			])
            ;
    }

    /**
     * @return string
     */
    public function getEntity(){
        return Unit::class;
    }

    /**
     * @return string
     */
    public function getName()
	{
		return 'unit-datatable';
	}
}