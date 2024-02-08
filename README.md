## TP Final docker avancé

Cloner le repo :

```bash
git clone https://github.com/BDanielo/finalDocker.git
```

# Docker file

Build l'image

```bash
docker build -t symfony-tpfinal .
```

# Docker Compose

Lors du premier lancement du docker compose faire un build

```bash
docker compose build
```

Puis lancer le docker compose

```bash
docker compose up
```

Si la bdd ne s'initialize pas relancer faire un down puis un up (ça ne devrais pas le faire normalement)

# Docker compose swarm

Lancer le swarm

```bash
docker run --privileged -d --name swarm-master docker:dind
docker run --privileged -d --name swarm-node1 docker:dind
docker run --privileged -d --name swarm-node2 docker:dind
```

Init le swarm master

```bash
docker exec -it swarm-master sh
docker swarm ini
```

Init les nodes (changer le join selon celui decrit dans le master)

```bash
docker exec -it swarm-node1 sh
docker swarm join --token SWMTKN-1-5wo5d7kuonb8apz0xuip
j1gffknzlph2ox2f6vc3yxppfasiwn-a530xxhgxks8w66vsmkzbsf53 17
2.17.0.9:2377
```

pareil pour node 2 

```bash
docker exec -it swarm-node2 sh
docker swarm join --token SWMTKN-1-5wo5d7kuonb8apz0xuip
j1gffknzlph2ox2f6vc3yxppfasiwn-a530xxhgxks8w66vsmkzbsf53 17
2.17.0.9:2377
```

dans le master deploy la stack 

```bash
cp docker-compose-swarm.yml swarm-master:/
docker exec -it swarm-master sh
docker stack deploy -c docker-compose.yml symfony-swarm
```

