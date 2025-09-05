// Importa los tipos correctos desde 'react'
import type { ComponentType, ReactElement } from 'react';
export interface PageModuleInterface {
  default: ComponentType; // El componente principal de la página
  layout?: (page: ReactElement) => ReactElement;
}