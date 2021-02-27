require 'yaml'
require 'fileutils'

required_plugins = %w( vagrant-hostmanager vagrant-vbguest )
required_plugins.each do |plugin|
    exec "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
end

config = {
  local: './vagrant/config/vagrant-local.yml',
  example: './vagrant/config/vagrant-local.example.yml'
}

# copy config from example if local config not exists
FileUtils.cp config[:example], config[:local] unless File.exist?(config[:local])
# read config
options = YAML.load_file config[:local]

domains = {
  frontend: 'front.' + options['domain'],
  backend:  'back.' + options['domain'],
  api:  'api.' + options['domain'],
  adminer:  'db.' + options['domain'],
  kibana:  'kibana.' + options['domain'],
  requirements:  'req.' + options['domain'],
  elasticsearch:  'es.' + options['domain'],
  websocket:  'ws.' + options['domain']
}

# check github token
if options['github_token'].nil? || options['github_token'].to_s.length != 40
  puts "You must place REAL GitHub token into configuration:\n/yii2-app-advanced/vagrant/config/vagrant-local.yml"
  exit
end

# vagrant configure
Vagrant.configure(2) do |config|
  # select the box
  config.vm.box = 'bento/ubuntu-20.04'

  # should we ask about box updates?
  config.vm.box_check_update = options['box_check_update']

  config.vm.provider 'virtualbox' do |vb|
    # machine cpus count
    vb.cpus = options['cpus']
    # machine memory size
    vb.memory = options['memory']
    # machine name (for VirtualBox UI)
    vb.name = options['machine_name']
  end

  # machine name (for vagrant console)
  config.vm.define options['machine_name']

  # machine name (for guest machine console)
  config.vm.hostname = options['machine_name']

  # network settings
  config.vm.network 'private_network', ip: options['ip']

  # sync: folder 'yii2-app-advanced' (host machine) -> folder '/app' (guest machine)
  config.vm.synced_folder './', '/app', owner: 'vagrant', group: 'vagrant'

  # disable folder '/vagrant' (guest machine)
  config.vm.synced_folder '.', '/vagrant', disabled: true

  # hosts settings (host machine)
  config.vm.provision :hostmanager
  config.hostmanager.enabled            = true
  config.hostmanager.manage_host        = true
  config.hostmanager.ignore_private_ip  = false
  config.hostmanager.include_offline    = true
  config.hostmanager.aliases            = domains.values

  # Elasticsearch
  config.vm.network "forwarded_port", guest: 9200, host: 9200

  # provisioners
  config.vm.provision 'shell', path: './vagrant/provision/once-as-root.sh', args: [options['timezone'], options['domain'], options['database'], options['database_test'], options['ip'], domains[:websocket]]
  config.vm.provision 'shell', path: './vagrant/provision/once-as-vagrant.sh', args: [options['github_token'], options['email'], options['username'], options['password'], options['role']], privileged: false
  config.vm.provision 'shell', path: './vagrant/provision/always-as-root.sh', run: 'always'
  # post-install message (vagrant console)
  config.vm.post_up_message = "Frontend URL: https://#{domains[:frontend]}\nBackend URL: https://#{domains[:backend]}\nAPI URL: https://#{domains[:api]}\nAdminer URL: https://#{domains[:adminer]}\nElasticsearch URL: https://#{domains[:elasticsearch]}\nKibana URL: https://#{domains[:kibana]}\nWebsocket URL: https://#{domains[:websocket]}\nRequirements URL: https://#{domains[:requirements]}\n\n\nAfter Install run the following on Ubuntu (Linux):\nsudo cp -R vagrant/nginx/ssl/root/*.crt /usr/local/share/ca-certificates/.\nsudo update-ca-certificates\n\n\nFor more information to install CA ROOt Certificates visit:\nhttps://www.bounca.org/tutorials/install_root_certificate.html\n\nYou might need to add the root certificate in Chrome -> Settings -> Manage Certificates -> Authorities ->  Import -> Trust Everything"
end
