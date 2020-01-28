<?php
	
	
	namespace App\Forms;
	
	
	use Symfony\Bridge\Doctrine\Form\Type\EntityType;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class UserPasswordType extends AbstractType
	{
		
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			parent::buildForm($builder, $options);
			$builder
				->add('oldPassword', PasswordType::class, [
					
					'label' => 'Huidig wachtwoord',
					'required' => true
				
				])
				->add('newPassword', PasswordType::class, [
					
					'label' => 'Nieuw Wachtwoord',
					'required' => true
				
				])
				->add('newRePassword', PasswordType::class, [
					
					'label' => 'Herhaal nieuw wachtwoord',
					'required' => true
				
				]);
		}
		
	}