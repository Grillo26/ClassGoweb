<?php

namespace App\Spotlight;

use Illuminate\Support\Facades\Auth;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class perfildetailsearch extends SpotlightCommand
{
    /**
     * This is the name of the command that will be shown in the Spotlight component.
     */
    protected string $name = 'Detalles del Perfil';

    /**
     * This is the description of your command which will be shown besides the command name.
     */
    protected string $description = 'Redirigir a la página de detalles del perfil';

    /**
     * You can define any number of additional search terms (also known as synonyms)
     * to be used when searching for this command.
     */
    protected array $synonyms = ['perfil', 'verificacion', 'configuracion', 'curriculum'];

    /**
     * When all dependencies have been resolved the execute method is called.
     * You can type-hint all resolved dependency you defined earlier.
     */
    public function execute(Spotlight $spotlight)
    {
        $user = Auth::user();

        $roles = $user->roles()->pluck('name')->toArray(); // o 'slug' si usas slugs

        if (in_array('student', $roles)) {
            return $spotlight->redirectRoute('student.profile.personal-details');
        }

        if (in_array('tutor', $roles)) {
            return $spotlight->redirectRoute('tutor.profile.personal-details');
        }

        // Si no tiene ninguno de los roles válidos
        return;
    }

    /**
     * You can provide any custom logic you want to determine whether the
     * command will be shown in the Spotlight component. If you don't have any
     * logic you can remove this method. You can type-hint any dependencies you
     * need and they will be resolved from the container.
     */
    public function shouldBeShown(): bool
    {
        return true;
    }
}
